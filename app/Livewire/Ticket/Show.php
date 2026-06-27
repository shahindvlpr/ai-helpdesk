<?php
// app/Livewire/Ticket/Show.php
namespace App\Livewire\Ticket;

use App\Models\Ticket;
use App\Models\Message;
use App\Services\AIService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public $ticket;
    public $message = '';
    public $aiSuggestion = '';
    public $loadingAI = false;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket->load(['user', 'agent', 'category', 'messages.user']);
    }

    public function sendMessage()
    {
        $this->validate([
            'message' => 'required|string|min:1',
        ]);

        $message = Message::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'message' => $this->message,
            'type' => Auth::user()->isCustomer() ? 'customer' : 'agent',
            'is_internal' => false,
        ]);

        $this->message = '';
        $this->ticket->refresh();
        $this->ticket->load(['messages.user']);
    }

    public function getAISuggestion()
    {
        $this->loadingAI = true;
        
        try {
            $aiService = app(AIService::class);
            $messages = $this->ticket->messages()
                ->latest()
                ->take(5)
                ->get()
                ->map(function ($msg) {
                    return $msg->user->name . ': ' . $msg->message;
                })
                ->implode("\n");

            $this->aiSuggestion = $aiService->suggestReply(
                $this->ticket->description,
                $messages
            );
        } catch (\Exception $e) {
            $this->aiSuggestion = 'Unable to generate AI suggestion. Please try again.';
        }

        $this->loadingAI = false;
    }

    public function resolveTicket()
    {
        $this->ticket->resolve();
        $this->ticket->refresh();
        session()->flash('success', 'Ticket resolved successfully!');
    }

    public function render()
    {
        return view('livewire.ticket.show');
    }
}