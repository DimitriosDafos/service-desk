<?php

namespace App\Http\Controllers\Ticketing;

use App\Http\Controllers\Controller;
use App\Ticketing\Models\Ticket;
use App\Ticketing\Models\TicketComment;
use App\Tenancy\Models\Queue;
use App\Tenancy\Models\Group;
use App\Models\User;
use App\SLA\Models\SlaPolicy;
use App\Ticketing\Enums\TicketStatus;
use App\Ticketing\Enums\TicketPriority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        $query = Ticket::where('tenant_id', $tenantId);

        if ($user->isRequester()) {
            $query->where('requester_id', $user->id);
        } elseif ($user->isAgent()) {
            $queueIds = $user->getQueueIds();
            $query->where(function ($q) use ($user, $queueIds) {
                $q->where('assigned_agent_id', $user->id);
                if (!empty($queueIds)) {
                    $q->orWhereIn('queue_id', $queueIds);
                } else {
                    $q->orWhereRaw('1=1');
                }
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        if ($request->queue_id) {
            $query->where('queue_id', $request->queue_id);
        }

        $tickets = $query->with(['requester', 'assignedAgent', 'assignedGroup', 'queue'])
            ->orderByDesc('created_at')
            ->paginate(20);

        $queues = Queue::where('tenant_id', $tenantId)->get();

        return view('tickets.index', compact('tickets', 'queues'));
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;
        $queues = Queue::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $groups = Group::where('tenant_id', $tenantId)->where('is_active', true)->get();
        $priorities = TicketPriority::values();
        $slaPolicies = SlaPolicy::where('tenant_id', $tenantId)->where('is_active', true)->get();

        return view('tickets.create', compact('queues', 'groups', 'priorities', 'slaPolicies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'queue_id' => 'nullable|uuid|exists:queues,id',
            'assigned_group_id' => 'nullable|uuid|exists:groups,id',
            'priority' => 'required|string',
            'impact' => 'nullable|string',
            'sla_policy_id' => 'nullable|uuid|exists:sla_policies,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['requester_id'] = auth()->user()->id;
        $validated['ticket_number'] = Ticket::generateTicketNumber($validated['tenant_id']);

        if (empty($validated['impact'])) {
            $validated['impact'] = $validated['priority'];
        }

        $ticket = Ticket::create($validated);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);
        $ticket->load(['requester', 'assignedAgent', 'assignedGroup', 'queue', 'slaPolicy', 'comments.user', 'tags', 'linkedAssets']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);
        $tenantId = auth()->user()->tenant_id;
        $queues = Queue::where('tenant_id', $tenantId)->get();
        $groups = Group::where('tenant_id', $tenantId)->get();
        $agents = User::where('tenant_id', $tenantId)->whereIn('role', ['tenant_admin', 'agent'])->get();
        $slaPolicies = SlaPolicy::where('tenant_id', $tenantId)->get();

        return view('tickets.edit', compact('ticket', 'queues', 'groups', 'agents', 'slaPolicies'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'queue_id' => 'nullable|uuid|exists:queues,id',
            'assigned_group_id' => 'nullable|uuid|exists:groups,id',
            'assigned_agent_id' => 'nullable|uuid|exists:users,id',
            'priority' => 'required|string',
            'impact' => 'nullable|string',
            'status' => 'required|string',
            'sla_policy_id' => 'nullable|uuid|exists:sla_policies,id',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update($validated);

        if ($oldStatus !== $ticket->status && $ticket->status === TicketStatus::IN_PROGRESS && !$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        if (in_array($ticket->status, [TicketStatus::RESOLVED, TicketStatus::CLOSED]) && !$ticket->resolved_at) {
            $ticket->update(['resolved_at' => now()]);
        }

        if ($ticket->status === TicketStatus::CLOSED && !$ticket->closed_at) {
            $ticket->update(['closed_at' => now()]);
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorizeTicket($ticket);
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $this->authorizeTicket($ticket);

        $validated = $request->validate([
            'content' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['is_internal'] = $validated['is_internal'] ?? false;

        $comment = $ticket->comments()->create($validated);

        if ($ticket->status === TicketStatus::NEW && !$ticket->first_response_at) {
            $ticket->update(['first_response_at' => now()]);
        }

        return back()->with('success', 'Comment added successfully');
    }

    protected function authorizeTicket($ticket)
    {
        $user = auth()->user();

        if ($user->isSystemLevel()) {
            return;
        }

        if ($ticket->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if ($user->isRequester() && $ticket->requester_id !== $user->id) {
            abort(403);
        }

        if ($user->isAgent()) {
            $queueIds = $user->getQueueIds();
            if ($ticket->assigned_agent_id !== $user->id && !in_array($ticket->queue_id, $queueIds)) {
                abort(403);
            }
        }
    }
}
