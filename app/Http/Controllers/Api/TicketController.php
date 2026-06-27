// app/Http/Controllers/Api/TicketController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Message;
use App\Services\TicketService;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    protected $ticketService;
    protected $aiService;

    public function __construct(TicketService $ticketService, AIService $aiService)
    {
        $this->ticketService = $ticketService;
        $this->aiService = $aiService;
    }

    /**
     * List all tickets
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $tickets = Ticket::with(['user', 'agent', 'category'])->latest()->paginate(15);
        } elseif ($user->isAgent()) {
            $tickets = Ticket::where('agent_id', $user->id)
                ->orWhereNull('agent_id')
                ->with(['user', 'agent', 'category'])
                ->latest()
                ->paginate(15);
        } else {
            $tickets = Ticket::where('user_id', $user->id)
                ->with(['agent', 'category'])
                ->latest()
                ->paginate(15);
        }

        return response()->json($tickets);
    }

    /**
     * Create a new ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $ticket = $this->ticketService->createTicket($validated, Auth::user());

        return response()->json([
            'message' => 'Ticket created successfully',
            'ticket' => $ticket->load(['user', 'agent', 'category']),
        ], 201);
    }

    /**
     * Show a specific ticket
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        return response()->json([
            'ticket' => $ticket->load(['user', 'agent', 'category', 'messages.user']),
        ]);
    }

    /**
     * Update ticket
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $validated = $request->validate([
            'subject' => 'sometimes|string|max:255',
            'priority' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:open,in_progress,resolved,closed',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        $ticket->update($validated);

        return response()->json([
            'message' => 'Ticket updated successfully',
            'ticket' => $ticket->fresh(['user', 'agent', 'category']),
        ]);
    }

    /**
     * Assign agent to ticket
     */
    public function assignAgent(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        $agent = User::find($validated['agent_id']);
        $ticket = $this->ticketService->assignAgent($ticket, $agent);

        return response()->json([
            'message' => 'Agent assigned successfully',
            'ticket' => $ticket->load(['user', 'agent', 'category']),
        ]);
    }

    /**
     * Resolve ticket
     */
    public function resolve(Request $request, Ticket $ticket)
    {
        $this->authorize('resolve', $ticket);

        $validated = $request->validate([
            'resolution_note' => 'sometimes|string',
        ]);

        $ticket = $this->ticketService->resolveTicket(
            $ticket,
            $validated['resolution_note'] ?? null
        );

        return response()->json([
            'message' => 'Ticket resolved successfully',
            'ticket' => $ticket->load(['user', 'agent', 'category']),
        ]);
    }

    /**
     * Add message to ticket
     */
    public function addMessage(Request $request, Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'sometimes|boolean',
        ]);

        $message = $this->ticketService->addMessage(
            $ticket,
            Auth::user(),
            $validated['message'],
            $validated['is_internal'] ?? false
        );

        return response()->json([
            'message' => 'Message added successfully',
            'data' => $message->load('user'),
        ]);
    }

    /**
     * Get AI suggestion for ticket
     */
    public function getAISuggestion(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $messages = $ticket->messages()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($msg) {
                return $msg->user->name . ': ' . $msg->message;
            })
            ->implode("\n");

        $suggestion = $this->aiService->suggestReply(
            $ticket->description,
            $messages
        );

        return response()->json([
            'suggestion' => $suggestion,
        ]);
    }

    /**
     * Get ticket statistics
     */
    public function stats()
    {
        $stats = $this->ticketService->getStats();
        
        return response()->json($stats);
    }

    /**
     * Search tickets
     */
    public function search(Request $request)
    {
        $filters = $request->only([
            'status', 'priority', 'category_id', 'agent_id', 
            'user_id', 'search', 'date_from', 'date_to', 'per_page'
        ]);

        $tickets = $this->ticketService->searchTickets($filters);

        return response()->json($tickets);
    }
}