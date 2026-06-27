<?php
// app/Livewire/Ticket/Edit.php
namespace App\Livewire\Ticket;

use App\Models\Ticket;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public $ticket;
    public $subject;
    public $description;
    public $priority;
    public $category_id;
    public $categories;

    public function mount(Ticket $ticket)
    {
        $this->ticket = $ticket;
        $this->subject = $ticket->subject;
        $this->description = $ticket->description;
        $this->priority = $ticket->priority;
        $this->category_id = $ticket->category_id;
        $this->categories = Category::active()->get();
        
        // Authorize
        if (!Auth::user()->can('update', $ticket)) {
            abort(403);
        }
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

    public function update()
    {
        $this->validate();

        $this->ticket->update([
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'category_id' => $this->category_id,
        ]);

        session()->flash('success', 'Ticket updated successfully!');
        return redirect()->route('tickets.show', $this->ticket);
    }

    public function render()
    {
        return view('livewire.ticket.edit');
    }
}