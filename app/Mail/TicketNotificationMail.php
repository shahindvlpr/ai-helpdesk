// app/Mail/TicketNotificationMail.php
<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $event;

    public function __construct(Ticket $ticket, string $event = 'created')
    {
        $this->ticket = $ticket;
        $this->event = $event;
    }

    public function build()
    {
        $subject = match($this->event) {
            'created' => "New Ticket #{$this->ticket->ticket_id}",
            'assigned' => "Ticket #{$this->ticket->ticket_id} Assigned",
            'resolved' => "Ticket #{$this->ticket->ticket_id} Resolved",
            'updated' => "Ticket #{$this->ticket->ticket_id} Updated",
            default => "Ticket #{$this->ticket->ticket_id} Notification"
        };

        return $this->subject($subject)
                    ->markdown('emails.ticket-notification')
                    ->with([
                        'ticket' => $this->ticket,
                        'event' => $this->event,
                        'ticketUrl' => route('tickets.show', $this->ticket),
                    ]);
    }
}