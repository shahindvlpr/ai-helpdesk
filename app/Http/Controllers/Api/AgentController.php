// app/Http/Controllers/Api/AgentController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    /**
     * Get all agents
     */
    public function index(Request $request)
    {
        $query = Agent::with('user');

        if ($request->has('department')) {
            $query->byDepartment($request->department);
        }

        if ($request->has('available')) {
            $query->available();
        }

        return response()->json($query->get());
    }

    /**
     * Get agent details
     */
    public function show(Agent $agent)
    {
        return response()->json([
            'agent' => $agent->load('user'),
            'current_tickets' => $agent->getCurrentTicketCount(),
            'available_slots' => $agent->getAvailableSlots(),
            'tickets' => $agent->tickets()->latest()->take(5)->get()
        ]);
    }

    /**
     * Get agent's tickets
     */
    public function tickets(Agent $agent, Request $request)
    {
        $query = $agent->tickets()->with(['user', 'category']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(15));
    }

    /**
     * Assign ticket to agent
     */
    public function assignTicket(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $ticket = Ticket::find($validated['ticket_id']);
        
        if (!$agent->isAvailable()) {
            return response()->json([
                'message' => 'Agent is not available',
                'available_slots' => $agent->getAvailableSlots()
            ], 400);
        }

        $ticket->assignTo($agent->user);

        // Log activity
        ActivityLog::log(
            Auth::id(),
            'assign_ticket',
            "Ticket #{$ticket->ticket_id} assigned to agent {$agent->user->name}",
            ['agent_id' => $agent->id, 'ticket_id' => $ticket->id]
        );

        return response()->json([
            'message' => 'Ticket assigned successfully',
            'ticket' => $ticket->load(['user', 'agent', 'category'])
        ]);
    }

    /**
     * Update agent availability
     */
    public function updateAvailability(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $agent->update(['is_available' => $validated['is_available']]);

        return response()->json([
            'message' => 'Agent availability updated',
            'agent' => $agent->load('user')
        ]);
    }
}