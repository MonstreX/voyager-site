<?php

namespace MonstreX\VoyagerSite\Notifications;

use http\Env\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class SendForm extends Notification
{
    use Queueable;

    protected $formFields;

    protected $fields;

    protected $request;

    public function __construct($formFields, $request)
    {
        $this->request = $request;
        $this->formFields = $formFields;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $new_mail = (new MailMessage);

        if ($this->formFields['subject']) {
            $new_mail = $new_mail->subject($this->formFields['subject']);
        }

        $new_mail = $new_mail->greeting(__('mail.greeting'));

        foreach ($this->formFields as $key => $field) {
            if (!is_array($field)) {
                $title = __('mail.' . $key);
                $new_mail = $new_mail->line(new HtmlString('<p><strong>' . $title .'</strong>: ' . $field . '</p>'));
            }
        }

        $new_mail = $new_mail->salutation(__('mail.salutation'));

        // Attach files if present
        $file_fields = $this->request->file();
        if(count($file_fields) > 0) {
            foreach ($file_fields as $key => $files) {
                if(count($files) > 0) {
                    foreach ($files as $file) {
                        $new_mail->attach($file->getRealPath(), array(
                                'as' => $file->getClientOriginalName(),
                                'mime' => $file->getMimeType())
                        );
                    }
                }
            }
        }

        return $new_mail;
    }
}
