<?php


namespace MonstreX\VoyagerSite;

use Illuminate\Database\Eloquent\Model;
use MonstreX\VoyagerSite\Models\SiteSetting as Settings;
use MonstreX\VoyagerSite\Notifications\SendForm;
use Illuminate\Support\Facades\Mail;
use MonstreX\VoyagerSite\Mail\InfoMailable;
use Notification;

class VoyagerSite
{
    protected $setting_cache = [];


    /*
     * Site Settings
     */
    public function setting($key, $default = null)
    {
        $keys = explode('.', $key);

        $alias = $keys[0];

        $settings = Settings::where('key', $alias)->first();

        $config = json_decode($settings->details);

        if (!isset($this->setting_cache[$alias]) || $this->setting_cache[$alias] === null) {

            foreach ($config->fields as $keyField => $field) {
                if($field->type != 'section') {
                    $this->setting_cache[$alias][$keyField] = $field->value;
                }
            }

        }

        return $this->setting_cache[$keys[0]][$keys[1]] ?: $default;
    }


    public function storeMediaFile(Model $class, $field):string
    {
        $result = $class->addMediaFromRequest($field)->toMediaCollection($field);
        return $result;
    }


    public function mail()
    {
        // 1
        $name = 'Krunal';
        Mail::to('fido6080net@mail.ru')->send(new InfoMailable($name));

        // 2
        //$formFields = $this->request->except(['q', '_token', '_form_alias', '_validator']);
        $formFields = [
            'subject' => 'New subject for email',
            'name' => 'Test NAME',
            'phone'  => '02392-304932034',
            'email'  => 'fodd@mail.ru',
            'message' => 'LONG MESSAGE TEXT',
        ];

        $emails = explode(',', 'fido6080net@mail.ru,fido6080net@gmail.com');
        Notification::route('mail', $emails)->notify(new SendForm($formFields));

    }


    public function currentPath(): string
    {
        return request()->path();
    }

}