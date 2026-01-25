<?php

namespace MonstreX\VoyagerSite\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Notification;
use Validator;
use Arr;
use Str;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use TCG\Voyager\Services\Media\MediaService;

use MonstreX\VoyagerSite\Notifications\SendForm;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use VBlock;

class VoyagerSiteController extends VoyagerBaseController
{

    /*
     *  Settings EDIT
     */
    public function settingsEdit($key)
    {

        $dataType = Voyager::model('DataType')->where('slug', '=', 'site-settings')->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        $settings = Settings::where('key',$key)->first();

        return view('voyager-site::settings.index')->with(['key' => $key, 'title' => $settings->title, 'config' => json_decode($settings->details), 'settings' => $settings]);
    }

    /*
     *  Settings UPDATE
     */
    public function settingsUpdate(Request $request, $key)
    {

        $dataType = Voyager::model('DataType')->where('slug', '=', 'site-settings')->first();

        // Check permission
        $this->authorize('edit', app($dataType->model_name));

        $settings = Settings::where('key', $key)->first();

        $config = json_decode($settings->details);

        if($request->has('remove_image')) {
            // ONLY clear image field and remove file
            foreach ($config->fields as $key_field => $field) {
                if ($key_field === trim($request->remove_image)) {
                    $file = json_decode($config->fields->{$key_field}->value);
                    $this->deleteFileIfExists($file[0]->download_link);
                    $config->fields->{$key_field}->value = '';
                }
            }
        } elseif($request->has('remove_media')) {
            foreach ($config->fields as $key_field => $field) {
                if ($key_field === trim($request->remove_media)) {
                    $mediaItems = $settings->getMedia($key_field);
                    if ($mediaItems && $mediaItems->count()) {
                        $service = app(MediaService::class);
                        $mediaItems->each(function ($media) use ($service) {
                            $service->deleteMedia($media);
                        });
                    }
                    $config->fields->{$key_field}->value = '';
                }
            }
        } else {
            // Save all data from Request
            foreach ($config->fields as $key_field => $field) {
                if($field->type === 'text' || $field->type === 'number' || $field->type === 'textarea' || $field->type === 'code_editor' || $field->type === 'rich_text_box') {
                    $config->fields->{$key_field}->value = $request->{$key_field};
                } elseif ($field->type === 'checkbox') {
                    $config->fields->{$key_field}->value = isset($request->{$key_field})? '1' : '0';
                } elseif ($field->type === 'radio' || $field->type === 'dropdown') {
                    $config->fields->{$key_field}->value = $request->{$key_field};
                } elseif ($field->type === 'image') {
                    if($request->hasFile($key_field)) {
                        $config->fields->{$key_field}->value = store_post_files($request, 'settings', $key_field);
                    }
                } elseif ($field->type === 'media') {
                    if($request->hasFile($key_field)) {
                        $mediaItems = $settings->getMedia($key_field);
                        if ($mediaItems && $mediaItems->count()) {
                            $service = app(MediaService::class);
                            $mediaItems->each(function ($media) use ($service) {
                                $service->deleteMedia($media);
                            });
                        }

                        $media = $settings->addMedia($request->file($key_field), $key_field)->save();
                        $config->fields->{$key_field}->value = $media ? $media->id : '';
                    }
                }
            }
        }

        $settings->details = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $settings->save();

        return redirect()->route('voyager.site-settings.index');
    }


    /*
     *  Send Form
     */
    public function sendForm(Request $request)
    {
        $message_type = 'unknown';
        $messages = [];
        $validator = null;

        $formAlias = $request->input('_form_alias');
        if (!$formAlias) {
            return $this->respondForm($request, 'error', ['Form alias is missing.']);
        }

        $form = VBlock::getFormByKey($formAlias);
        if (!$form) {
            return $this->respondForm($request, 'error', ['Form not found.']);
        }

        $options = json_decode($form->details ?? '') ?: (object) [];

        if (isset($options->validator)) {
            $validator_messages = isset($options->messages) ? (array) $options->messages : [];
            $validator = Validator::make($request->all(), (array) $options->validator, $validator_messages);
            if (!$validator->passes()) {
                $message_type = 'error-validation';
                $messages = $validator->errors()->all();
                return $this->respondForm($request, $message_type, $messages, $validator);
            }
        }

        $formFields = $request->except(['_token', '_form_alias', '_mail_to', 'g-recaptcha-response']);

        try {
            $to_address = str_replace(' ', '', $options->to_address ?? site_setting('mail.to_address'));

            if ($request->filled('_mail_to')) {
                $to_address = site_setting($request->input('_mail_to'), $to_address);
            }

            $emails = collect(explode(',', (string) $to_address))
                ->map(function ($email) {
                    return trim($email);
                })
                ->filter()
                ->values()
                ->all();

            if (empty($emails)) {
                throw new \RuntimeException('Email address is not configured.');
            }

            Notification::route('mail', $emails)->notify(new SendForm($formFields, $request));

            $message_type = 'success';
            $messages[] = __('voyager-site::mail.send_for_success_message');
        } catch (\Throwable $e) {
            $message_type = 'error';
            $messages[] = $e->getMessage();
            Log::alert('voyager-site::mail.log_error_message'. $e->getMessage());
        }

        return $this->respondForm($request, $message_type, $messages, $validator);
    }

    protected function respondForm(Request $request, string $type, array $messages, $validator = null)
    {
        if ($request->ajax()) {
            $messages_html = '<ul>';
            foreach ($messages as $message) {
                $messages_html .= '<li>'.e($message).'</li>';
            }
            $messages_html .= '</ul>';
            return response()->json(['type' => $type, 'messages' => $messages_html], $type === 'success' ? 200 : 422);
        }

        $response = redirect()->back()->withInput();

        if ($validator instanceof \Illuminate\Contracts\Support\MessageBag || $validator instanceof \Illuminate\Validation\Validator) {
            $response = $response->withErrors($validator);
        } elseif ($type === 'error-validation') {
            $bag = new MessageBag();
            foreach ($messages as $message) {
                $bag->add('form', $message);
            }
            $response = $response->withErrors($bag);
        }

        return $response->with([
            'form_status' => $type,
            'form_messages' => $messages,
        ]);
    }



    /*
     *  Send TEST Email
     */
    public function sendTestMail(Request $request)
    {
        $formFields = [
            'subject' => __('voyager-site::mail.send_test_mail_subject') . site_setting('general.site_app_name'),
            'message' => __('voyager-site::mail.send_test_mail_message'),
        ];

        try {
            $to_address = site_setting('mail.to_address');
            $emails = collect(explode(',', (string) $to_address))
                ->map(function ($email) {
                    return trim($email);
                })
                ->filter()
                ->values()
                ->all();

            if (empty($emails)) {
                return view('voyager-site::settings.mail-sent')->with([
                    'title' => __('voyager-site::mail.send_test_mail_title'),
                    'content' => null,
                    'error' => 'Email address is not configured.',
                ]);
            }

            Notification::route('mail', $emails)->notify(new SendForm($formFields, $request));

            return view('voyager-site::settings.mail-sent')->with([
                'title' => __('voyager-site::mail.send_test_mail_title'),
                'content' => __('voyager-site::mail.send_test_mail_success_message'),
                'error' => null,
            ]);
        } catch (\Throwable $e) {
            return view('voyager-site::settings.mail-sent')->with([
                'title' => __('voyager-site::mail.send_test_mail_title'),
                'content' => null,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getFileName($file)
    {
        $fullName = $file->getClientOriginalName();
        $filename = pathinfo($fullName, PATHINFO_FILENAME);
        $extension = pathinfo($fullName, PATHINFO_EXTENSION);

        return Str::slug($filename) . '.' . $extension;
    }

}
