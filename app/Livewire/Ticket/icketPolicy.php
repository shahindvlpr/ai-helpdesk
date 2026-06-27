<?php
// app/Policies/TicketPolicy.php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     * Admin can do everything.
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any tickets.
     */
    public function viewAny(User $user)
    {
        // All authenticated users can view tickets
        return true;
    }

    /**
     * Determine whether the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket)
    {
        // Admin can view any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can view tickets assigned to them or unassigned tickets
        if ($user->isAgent()) {
            return $ticket->agent_id === $user->id || $ticket->agent_id === null;
        }

        // Customer can view their own tickets
        return $ticket->user_id === $user->id;
    }

    /**
     * Determine whether the user can create tickets.
     */
    public function create(User $user)
    {
        // Any authenticated user can create tickets
        return true;
    }

    /**
     * Determine whether the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket)
    {
        // Admin can update any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can update tickets assigned to them
        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return true;
        }

        // Customer can update their own tickets only if open
        if ($user->isCustomer() && $ticket->user_id === $user->id && $ticket->isOpen()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket)
    {
        // Admin can delete any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can delete tickets assigned to them (only if closed or resolved)
        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return $ticket->status === 'closed' || $ticket->status === 'resolved';
        }

        // Customer can delete their own tickets (only if open)
        if ($user->isCustomer() && $ticket->user_id === $user->id && $ticket->isOpen()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the ticket.
     */
    public function restore(User $user, Ticket $ticket)
    {
        // Only admin can restore deleted tickets
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the ticket.
     */
    public function forceDelete(User $user, Ticket $ticket)
    {
        // Only admin can force delete
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can assign the ticket to an agent.
     */
    public function assign(User $user, Ticket $ticket)
    {
        // Admin can assign any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can assign tickets to themselves
        if ($user->isAgent()) {
            return $ticket->agent_id === $user->id || $ticket->agent_id === null;
        }

        return false;
    }

    /**
     * Determine whether the user can resolve the ticket.
     */
    public function resolve(User $user, Ticket $ticket)
    {
        // Admin can resolve any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can resolve tickets assigned to them
        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return true;
        }

        // Customer cannot resolve tickets (only agents/admin)
        return false;
    }

    /**
     * Determine whether the user can close the ticket.
     */
    public function close(User $user, Ticket $ticket)
    {
        // Admin can close any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can close tickets assigned to them
        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return true;
        }

        // Customer can close their own resolved tickets
        if ($user->isCustomer() && $ticket->user_id === $user->id && $ticket->isResolved()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can reopen the ticket.
     */
    public function reopen(User $user, Ticket $ticket)
    {
        // Admin can reopen any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can reopen tickets assigned to them
        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return true;
        }

        // Customer can reopen their own closed/resolved tickets
        if ($user->isCustomer() && $ticket->user_id === $user->id) {
            return $ticket->status === 'closed' || $ticket->status === 'resolved';
        }

        return false;
    }

    /**
     * Determine whether the user can add internal notes.
     */
    public function addInternalNote(User $user, Ticket $ticket)
    {
        // Admin and agents can add internal notes
        if ($user->isAdmin() || $user->isAgent()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view internal notes.
     */
    public function viewInternalNotes(User $user, Ticket $ticket)
    {
        // Admin and agents can view internal notes
        if ($user->isAdmin() || $user->isAgent()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can attach files to the ticket.
     */
    public function attachFiles(User $user, Ticket $ticket)
    {
        // All users can attach files to their tickets
        if ($user->isCustomer() && $ticket->user_id === $user->id) {
            return true;
        }

        if ($user->isAgent() && $ticket->agent_id === $user->id) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can escalate the ticket.
     */
    public function escalate(User $user, Ticket $ticket)
    {
        // Only admin and agents can escalate tickets
        return $user->isAdmin() || $user->isAgent();
    }

    /**
     * Determine whether the user can transfer the ticket.
     */
    public function transfer(User $user, Ticket $ticket)
    {
        // Only admin can transfer tickets to other agents
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view ticket analytics.
     */
    public function viewAnalytics(User $user)
    {
        // Only admin can view analytics
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can export tickets.
     */
    public function export(User $user)
    {
        // Only admin can export tickets
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can change ticket priority.
     */
    public function changePriority(User $user, Ticket $ticket)
    {
        // Admin and agents can change priority
        if ($user->isAdmin() || $user->isAgent()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can change ticket category.
     */
    public function changeCategory(User $user, Ticket $ticket)
    {
        // Admin and agents can change category
        if ($user->isAdmin() || $user->isAgent()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view ticket logs.
     */
    public function viewLogs(User $user, Ticket $ticket)
    {
        // Admin and agents can view logs
        if ($user->isAdmin() || $user->isAgent()) {
            return true;
        }

        // Customer can view logs of their own tickets
        return $ticket->user_id === $user->id;
    }
}