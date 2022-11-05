<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use OwenIt\Auditing\Models\Audit;

class SiteUpdated extends Notification //implements ShouldQueue
{
    use Queueable;

    public $site;
    public $audit;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site, Audit $audit)
    {
        $this->afterCommit();

        $this->site = $site;
        $this->audit = $audit;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'site_id' => $this->site->id,
            'site_url' => $this->site->url,
            'audit_id' => $this->audit->id,
            'audit_modified' => $this->audit->getModified(),
        ];
    }
}
