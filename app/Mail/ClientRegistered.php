<?php

namespace App\Mail;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $client;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New client')
            ->view('emails.client', [
                'name' => $this->client->name,
                'email' => $this->client->email,
                'created_at' => $this->client->created_at->format('d/m/Y H:i'),
                'id' => $this->client->id,
                'route' => route('clients.edit', $this->client->id),
                'subject' => 'New client',
            ]);
    }
}
