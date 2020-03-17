<?php

namespace MonstreX\VoyagerSite\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class SendForm extends Notification
{
    use Queueable;

    protected $formFields;

    protected $fields;

    public function __construct($formFields)
    {
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
            $title = __('mail.' . $key);
            $new_mail = $new_mail->line(new HtmlString('<p><strong>' . $title .'</strong>: ' . $field . '</p>'));
        }

        $new_mail = $new_mail->salutation(__('mail.salutation'));

        return $new_mail;
    }
}
