<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mail_from;
    protected $mail_from_name;
    protected $subject;
    protected $body_line1;
    protected $body_line2;
    protected $body_cta;
    protected $body_cta_link;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mail_from, $mail_from_name, $subject, $body_line1, $body_line2, $body_cta, $body_cta_link)
    {
      $this->mail_from = $mail_from;
      $this->mail_from_name = $mail_from_name;
      $this->subject = $subject;
      $this->body_line1 = $body_line1;
      $this->body_line2 = $body_line2;
      $this->body_cta = $body_cta;
      $this->body_cta_link = $body_cta_link;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      return (new MailMessage)
                  ->from($this->mail_from, $this->mail_from_name)
                  ->replyTo($this->mail_from, $this->mail_from_name)
                  ->subject($this->subject)
                  ->greeting(trans('nearby-platform.mail_greeting', ['name' => $notifiable->name]))
                  ->line($this->body_line1)
                  ->action($this->body_cta, $this->body_cta_link)
                  ->line($this->body_line2);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
