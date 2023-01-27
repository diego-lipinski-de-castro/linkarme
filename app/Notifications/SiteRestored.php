<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SiteRestored extends Notification
{
    use Queueable;

    public $site;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->afterCommit();

        $this->site = $site;
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
        ];
    }
}
