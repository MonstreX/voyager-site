<?php

namespace MonstreX\VoyagerSite\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Notification;
use Validator;
use Arr;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;

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
                    $settings->clearMediaCollection($key_field);
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
                        $settings->clearMediaCollection($key_field);
                        $media = $settings->addMediaFromRequest($key_field)->toMediaCollection($key_field);
                        $config->fields->{$key_field}->value = $media->id;
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
        if ($request->isMethod('post')) {

            $message_type = 'unknown';
            $messages = [];

            $formFields = $request->except(['_token', '_form_alias', '_mail_to', 'g-recaptcha-response']);

            if ($form = VBlock::getFormByKey($request->_form_alias)) {
                $options = json_decode($form->details);
            }

            // Validation
            if (isset($options->validator)) {
                $validator_messages = isset($options->messages)? (array) $options->messages : null;
                $validator = Validator::make($request->all(), (array) $options->validator, $validator_messages);
                if(!$validator->passes()) {
                    $errors = $validator->errors()->all();
                    $message_type = 'error-validation';
                    foreach ($errors as $error) {
                        $messages[] = $error;
                    }
                }
            }

//            // Check Captcha if present
//            if($request->has('g-recaptcha-response')) {
//                $secret = site_setting('general.site_captcha_secret_key');
//                $response = $request->input('g-recaptcha-response');
//                $ip = $_SERVER['REMOTE_ADDR'];
//                $res = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$response."&remoteip=".$ip);
//
//                $captcha = json_decode($res);
//
//                if (!$captcha->success) {
//                    $errors = true;
//                    $message_type = 'error';
//                    $messages[] = __('site.form_send_captcha_error');
//                }
//
//            }

            // Try to send if no errors
            if(!isset($errors)) {
                try {
                    // Use email_to address from global site settings or from the local form options
                    $to_address = str_replace(' ','', isset($options->to_address)?
                        $options->to_address :
                        site_setting('mail.to_address'));

                    // If we have override email to address - we use this one
                    if($request->has('_mail_to')) {
                        $to_address = site_setting($request->input('_mail_to'));
                    }

                    $emails = explode(',', $to_address);

                    Notification::route('mail', $emails)->notify(new SendForm($formFields, $request));

                    $message_type = 'success';
                    $messages[] = __('voyager-site::mail.send_for_success_message');

                } catch (\Swift_TransportException $e)  {
                    $message_type = 'error';
                    $messages[] = $e->getMessage();
                    \Log::alert('voyager-site::mail.log_error_message'. $e->getMessage());
                }
            }

        }

        if ($request->ajax()) {
            $messages_html = '<ul>';
            foreach ($messages as $message) {
                $messages_html .= '<li>'.$message.'</li>';
            }
            $messages_html .= '</ul>';
            return response()->json(['type' => $message_type, 'messages' => $messages_html]);
        } else {



            return redirect()->back()->withInput()->withErrors($validator);
            //->with(['form_status' => $message_type, 'form_messages' => $messages]);
        }
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

        $emails = explode(',', site_setting('mail.to_address'));
        try {
            Notification::route('mail', $emails)->notify(new SendForm($formFields, $request));
            $error = null;
        } catch (\Swift_TransportException $e) {
            $error = $e;
        }

        return view('voyager-site::settings.mail-sent')->with([
            'title' => __('voyager-site::mail.send_test_mail_title'),
            'content' => __('voyager-site::mail.send_test_mail_success_message'),
            'error' => $error
        ]);
    }

}
