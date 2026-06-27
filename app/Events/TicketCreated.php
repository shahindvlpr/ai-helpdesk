// app/Events/TicketCreated.php
<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('tickets');
    }

    public function broadcastWith()
    {
        return [
            'ticket_id' => $this->ticket->ticket_id,
            'subject' => $this->ticket->subject,
            'user' => $this->ticket->user->name,
            'created_at' => $this->ticket->created_at->toDateTimeString(),
        ];
    }
}