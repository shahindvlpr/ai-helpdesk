{{-- resources/views/livewire/ticket/show.blade.php --}}
<div>
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('tickets.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Tickets
        </a>
        <div class="flex space-x-2">
            @if(auth()->user()->isAgent() && !$ticket->agent_id)
                <button wire:click="assignToMe" 
                        class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 text-sm">
                    Assign to Me
                </button>
            @endif
            
            @if(auth()->user()->can('resolve', $ticket) && $ticket->status !== 'resolved')
                <button wire:click="resolveTicket" 
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm">
                    Resolve Ticket
                </button>
            @endif
            
            @if(auth()->user()->can('update', $ticket) && $ticket->status !== 'closed')
                <button wire:click="closeTicket" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                    Close Ticket
                </button>
            @endif

            {{-- ✅ Delete Button --}}
            @if(auth()->user()->can('delete', $ticket))
                <button wire:click="deleteTicket" 
                        wire:confirm="Are you sure you want to delete this ticket? This action cannot be undone!"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm">
                    <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Ticket
                </button>
            @endif
        </div>
    </div>

    @if(session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Ticket Header -->
        <div class="p-6 border-b">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        #{{ $ticket->ticket_id }} - {{ $ticket->subject }}
                    </h1>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $ticket->priority === 'critical' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $ticket->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ticket->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}
                        ">
                            Priority: {{ ucfirst($ticket->priority) }}
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $ticket->status === 'open' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $ticket->status === 'in_progress' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            Status: {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                        @if($ticket->category)
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ $ticket->category->name }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-sm text-gray-500 text-right">
                    <div>Created {{ $ticket->created_at->diffForHumans() }}</div>
                    @if($ticket->agent)
                        <div class="mt-1">Assigned to: <span class="font-medium">{{ $ticket->agent->name }}</span></div>
                    @else
                        <div class="mt-1 text-yellow-600">⚠️ Unassigned</div>
                    @endif
                </div>
            </div>

            <!-- AI Summary -->
            @if($ticket->ai_summary)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">AI Summary</p>
                            <p class="text-sm text-blue-700">{{ $ticket->ai_summary }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Ticket Description -->
        <div class="p-6 border-b bg-gray-50">
            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
            <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->description }}</p>
        </div>

        <!-- Messages -->
        <div class="p-6" style="max-height: 500px; overflow-y: auto;">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Messages</h3>
            
            @forelse($ticket->messages as $message)
                <div class="mb-4 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                    <div class="inline-block max-w-3/4 {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800' }} rounded-lg px-4 py-2">
                        <div class="text-xs font-medium {{ $message->user_id === auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                            {{ $message->user->name }} - {{ $message->created_at->format('M d, Y H:i') }}
                            @if($message->type === 'system')
                                <span class="ml-2 text-xs bg-gray-200 text-gray-600 px-1 rounded">System</span>
                            @endif
                        </div>
                        <p class="text-sm">{{ $message->message }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">No messages yet</p>
            @endforelse
        </div>

        <!-- AI Suggestion -->
        <div class="p-6 border-t">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-700">AI Suggested Reply</h3>
                <button wire:click="getAISuggestion" 
                        wire:loading.attr="disabled"
                        class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Get AI Suggestion
                </button>
            </div>

            @if($loadingAI)
                <div class="p-4 bg-gray-50 rounded-md text-center">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-sm text-gray-500">Generating AI suggestion...</p>
                </div>
            @elseif($aiSuggestion)
                <div class="p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-green-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-green-800 whitespace-pre-wrap">{{ $aiSuggestion }}</p>
                            <button wire:click="$set('message', '{{ addslashes($aiSuggestion) }}')" 
                                    class="mt-2 text-xs text-green-600 hover:text-green-800">
                                Use this reply →
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Reply Form -->
        <div class="p-6 border-t">
            <form wire:submit.prevent="sendMessage">
                <div class="mb-3">
                    <textarea wire:model="message" rows="3" 
                              placeholder="Type your reply here..." 
                              class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    @error('message')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Send Reply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>