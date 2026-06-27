// app/Services/TicketService.php
<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Message;
use App\Models\User;
use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use App\Jobs\ProcessTicket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketService
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Create a new ticket
     */
    public function createTicket(array $data, User $user): Ticket
    {
        return DB::transaction(function () use ($data, $user) {
            $ticket = Ticket::create([
                'ticket_id' => $this->generateTicketId(),
                'user_id' => $user->id,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'] ?? 'medium',
                'status' => 'open',
                'category_id' => $data['category_id'] ?? null,
                'metadata' => $data['metadata'] ?? null,
            ]);

            // Add initial message
            Message::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $data['description'],
                'type' => 'customer',
                'is_internal' => false,
            ]);

            // Process AI tasks in background
            ProcessTicket::dispatch($ticket);

            // Fire event
            event(new TicketCreated($ticket));

            return $ticket;
        });
    }

    /**
     * Generate unique ticket ID
     */
    protected function generateTicketId(): string
    {
        return 'TKT-' . strtoupper(Str::random(8));
    }

    /**
     * Assign agent to ticket
     */
    public function assignAgent(Ticket $ticket, User $agent): Ticket
    {
        if (!$agent->isAgent() && !$agent->isAdmin()) {
            throw new \Exception('User is not an agent');
        }

        $ticket->assignTo($agent);
        event(new TicketUpdated($ticket));

        return $ticket;
    }

    /**
     * Add message to ticket
     */
    public function addMessage(Ticket $ticket, User $user, string $message, bool $isInternal = false): Message
    {
        return DB::transaction(function () use ($ticket, $user, $message, $isInternal) {
            $messageModel = Message::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $message,
                'type' => $user->isCustomer() ? 'customer' : 'agent',
                'is_internal' => $isInternal,
            ]);

            // If ticket is open and agent replies, mark as in progress
            if ($ticket->isOpen() && $user->isAgent()) {
                $ticket->status = 'in_progress';
                $ticket->save();
            }

            // If customer replies to resolved ticket, reopen it
            if ($ticket->isResolved() && $user->isCustomer()) {
                $ticket->status = 'open';
                $ticket->save();
            }

            // Update response time
            if ($ticket->response_time === null && $user->isAgent()) {
                $ticket->response_time = $ticket->created_at->diffInMinutes(now());
                $ticket->save();
            }

            return $messageModel;
        });
    }

    /**
     * Resolve ticket
     */
    public function resolveTicket(Ticket $ticket, string $resolutionNote = null): Ticket
    {
        return DB::transaction(function () use ($ticket, $resolutionNote) {
            $ticket->resolve();

            if ($resolutionNote) {
                Message::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'message' => $resolutionNote,
                    'type' => 'system',
                    'is_internal' => true,
                ]);
            }

            event(new TicketUpdated($ticket));

            return $ticket;
        });
    }

    /**
     * Get ticket statistics
     */
    public function getStats(): array
    {
        return [
            'total' => Ticket::count(),
            'open' => Ticket::open()->count(),
            'in_progress' => Ticket::inProgress()->count(),
            'resolved' => Ticket::resolved()->count(),
            'closed' => Ticket::closed()->count(),
            'critical' => Ticket::priority('critical')->count(),
            'high' => Ticket::priority('high')->count(),
            'avg_resolution_time' => Ticket::resolved()->avg('resolution_time'),
            'avg_response_time' => Ticket::whereNotNull('response_time')->avg('response_time'),
        ];
    }

    /**
     * Get tickets for agent dashboard
     */
    public function getAgentTickets(User $agent, array $filters = []): array
    {
        $query = Ticket::assignedTo($agent->id);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return [
            'assigned' => $query->count(),
            'open' => $query->where('status', 'open')->count(),
            'in_progress' => $query->where('status', 'in_progress')->count(),
            'resolved' => $query->where('status', 'resolved')->count(),
            'tickets' => $query->latest()->paginate(15),
        ];
    }

    /**
     * Search tickets with filters
     */
    public function searchTickets(array $filters)
    {
        $query = Ticket::with(['user', 'agent', 'category']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['agent_id'])) {
            $query->where('agent_id', $filters['agent_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'LIKE', "%{$search}%")
                  ->orWhere('ticket_id', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }
}