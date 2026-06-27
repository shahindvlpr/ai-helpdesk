<!-- resources/views/livewire/ticket-list.blade.php -->
<div>
    <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">Tickets</h2>
        <a href="{{ route('tickets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
            Create Ticket
        </a>
    </div>

    <!-- Filters -->
    <div class="flex gap-4 mb-4">
        <input type="text" wire:model.live="search" placeholder="Search tickets..." class="px-4 py-2 border rounded">
        
        <select wire:model.live="status" class="px-4 py-2 border rounded">
            <option value="">All Status</option>
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
        </select>

        <select wire:model.live="priority" class="px-4 py-2 border rounded">
            <option value="">All Priority</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
        </select>
    </div>

    <!-- Tickets List -->
    <div class="space-y-4">
        @foreach($tickets as $ticket)
            <div class="border rounded p-4 hover:shadow">
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-bold">
                            <a href="{{ route('tickets.show', $ticket) }}">
                                #{{ $ticket->ticket_id }} - {{ $ticket->subject }}
                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm">{{ Str::limit($ticket->description, 100) }}</p>
                        <div class="flex gap-2 mt-2 text-xs">
                            <span class="px-2 py-1 rounded {{ $ticket->priority == 'high' ? 'bg-red-100 text-red-800' : 'bg-gray-100' }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                            <span class="px-2 py-1 rounded bg-blue-100 text-blue-800">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $ticket->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
</div>