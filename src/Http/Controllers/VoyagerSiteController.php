<?php

namespace MonstreX\VoyagerSite\Http\Controllers;

use Illuminate\Routing\Controller;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

class VoyagerSiteController extends VoyagerBaseController
{

    public function settingsEdit($key)
    {
        $settings = Settings::where('key',$key)->first();

        return view('voyager-site::settings.index')->with(['key' => $key, 'title' => $settings->title, 'config' => json_decode($settings->details)]);
    }


    public function settingsUpdate(Request $request, $key)
    {

        $dataType = Voyager::model('DataType')->where('slug', '=', 'site-settings')->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));


        $settings = Settings::where('key', $key)->first();

        $config = json_decode($settings->details);

        //dd($config);

        foreach ($config->fields as $key_field => $field) {
            if($field->type === 'text' || $field->type === 'textarea') {
                $config->fields->{$key_field}->value = $request->{$key_field};
            } elseif ($field->type === 'checkbox') {
                $config->fields->{$key_field}->value = isset($request->{$key_field})? '1' : '0';
            } elseif ($field->type === 'image') {
                if($request->hasFile($key_field)) {
                    $config->fields->{$key_field}->value = $this->storePostFiles($request, 'config', $key_field);
                }
            }
        }

        $settings->details = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $settings->save();

        return redirect()->route('voyager.site-settings.index');
    }


    private function storePostFiles(Request $request, $slug, $field, $public = true)
    {

        if (!$request->has($field)) {
            return json_encode([]);
        }

        $files = Arr::wrap($request->file($field));

        $filesPath = [];

        foreach ($files as $file) {
            $path = $slug.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR;
            $filename = $this->generateFileName($file, $path);

            $file->storeAs(
                $path,
                $filename.'.'.$file->getClientOriginalExtension(),
                config('voyager.storage.disk', 'public')
            );

            array_push($filesPath, [
                'download_link' => $path.$filename.'.'.$file->getClientOriginalExtension(),
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        return json_encode($filesPath);
    }

    private function generateFileName($file, $path)
    {
        $filename = basename(translit($file->getClientOriginalName()), '.' . $file->getClientOriginalExtension());
        $filename_counter = 1;
        // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
        while (Storage::disk(config('voyager.storage.disk'))->exists($path . $filename . '.' . $file->getClientOriginalExtension())) {
            $filename = basename(translit_ru($file->getClientOriginalName()), '.' . $file->getClientOriginalExtension()) . (string)($filename_counter++);
        }
        return $filename;
    }


}
