<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeekUpdatesOld extends Mailable
{
    use Queueable, SerializesModels;

    public $updates;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($updates)
    {
        $this->updates = $updates;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('WeekUpdates')
            ->view('emails.updates', [
                'updates' => $this->updates,
                'coins' => config('coins'),
                'subject' => 'WeekUpdates',
            ]);
    }
}
