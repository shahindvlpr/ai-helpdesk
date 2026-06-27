// app/Jobs/SendTicketNotification.php
<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Mail\TicketNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTicketNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;
    protected $event;

    public function __construct(Ticket $ticket, string $event = 'created')
    {
        $this->ticket = $ticket;
        $this->event = $event;
    }

    public function handle()
    {
        // Notify customer
        Mail::to($this->ticket->user->email)
            ->send(new TicketNotificationMail($this->ticket, $this->event));

        // Notify agent if assigned
        if ($this->ticket->agent) {
            Mail::to($this->ticket->agent->email)
                ->send(new TicketNotificationMail($this->ticket, 'assigned'));
        }

        // Create notification in database
        $this->ticket->user->notifications()->create([
            'ticket_id' => $this->ticket->id,
            'message' => "Ticket #{$this->ticket->ticket_id} has been {$this->event}",
            'type' => 'ticket_notification',
            'is_read' => false,
        ]);
    }
}