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
        
        // Check if user can view this ticket
        if (!$this->canView()) {
            abort(403, 'You are not authorized to view this ticket.');
        }
    }

    public function canView()
    {
        $user = Auth::user();
        if ($user->isAdmin()) return true;
        if ($user->isAgent()) return $this->ticket->agent_id === $user->id || $this->ticket->agent_id === null;
        return $this->ticket->user_id === $user->id;
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
        
        // If customer replies to resolved ticket, reopen it
        if ($this->ticket->isResolved() && Auth::user()->isCustomer()) {
            $this->ticket->status = 'open';
            $this->ticket->save();
        }
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
        $this->authorize('resolve', $this->ticket);
        $this->ticket->resolve();
        $this->ticket->refresh();
        session()->flash('success', 'Ticket resolved successfully!');
    }

    public function closeTicket()
    {
        $this->authorize('update', $this->ticket);
        $this->ticket->close();
        $this->ticket->refresh();
        session()->flash('success', 'Ticket closed successfully!');
    }

    public function assignToMe()
    {
        if (!Auth::user()->isAgent() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $this->ticket->assignTo(Auth::user());
        $this->ticket->refresh();
        session()->flash('success', 'Ticket assigned to you!');
    }

    public function render()
    {
        return view('livewire.ticket.show');
    }

public function deleteTicket()
{
    // Check permission
    if (!Auth::user()->can('delete', $this->ticket)) {
        session()->flash('error', 'You are not authorized to delete this ticket.');
        return;
    }
    
    // Delete the ticket
    $this->ticket->delete();
    
    session()->flash('success', 'Ticket #' . $this->ticket->ticket_id . ' deleted successfully!');
    
    // Redirect to ticket list
    return redirect()->route('tickets.index');
}
}