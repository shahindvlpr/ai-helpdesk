<?php
// app/Livewire/TicketList.php
namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class TicketList extends Component
{
    use WithPagination;

    public $status = '';
    public $priority = '';
    public $search = '';

    public function render()
    {
        $tickets = Ticket::query()
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->priority, fn($q) => $q->where('priority', $this->priority))
            ->when($this->search, fn($q) => $q->where('subject', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.ticket-list', compact('tickets'));
    }
}