<?php
// app/Livewire/Ticket/Create.php
namespace App\Livewire\Ticket;

use App\Models\Category;
use App\Models\Ticket;
use App\Services\TicketService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $subject;
    public $description;
    public $priority = 'medium';
    public $category_id;
    public $categories;

    public function mount()
    {
        $this->categories = Category::active()->get();
    }

    public function rules()
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,critical',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }

    public function save(TicketService $ticketService)
    {
        $this->validate();

        $ticket = $ticketService->createTicket([
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'category_id' => $this->category_id,
        ], Auth::user());

        session()->flash('success', 'Ticket created successfully!');
        
        return redirect()->route('tickets.show', $ticket);
    }

    public function render()
    {
        return view('livewire.ticket.create');
    }
}