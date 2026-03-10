<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use App\Tenancy\Models\Tenant;
use App\Models\User;
use App\Ticketing\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSystemLevel()) {
            return $this->systemDashboard();
        }

        return $this->tenantDashboard($user->tenant_id);
    }

    protected function systemDashboard()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'total_users' => User::count(),
            'total_tickets' => Ticket::count(),
        ];

        $recentTenants = Tenant::withCount('users')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentTenants'));
    }

    protected function tenantDashboard($tenantId)
    {
        $user = auth()->user();
        
        $query = Ticket::where('tenant_id', $tenantId);

        if ($user->isRequester()) {
            $query->where('requester_id', $user->id);
        } elseif ($user->isAgent()) {
            $queueIds = $user->getQueueIds();
            $query->where(function ($q) use ($user, $queueIds) {
                $q->where('assigned_agent_id', $user->id)
                  ->orWhereIn('queue_id', $queueIds);
            });
        }

        $tickets = $query->get();

        $stats = [
            'total_tickets' => $tickets->count(),
            'open_tickets' => $tickets->whereIn('status', ['new', 'triaged', 'in_progress', 'pending'])->count(),
            'closed_tickets' => $tickets->whereIn('status', ['resolved', 'closed', 'cancelled'])->count(),
            'my_tickets' => $user->isRequester() ? 0 : $tickets->where('assigned_agent_id', $user->id)->count(),
        ];

        $recentTickets = $tickets->take(10);
        $queues = \App\Tenancy\Models\Queue::where('tenant_id', $tenantId)->withCount('tickets')->get();

        return view('dashboard', compact('stats', 'recentTickets', 'queues'));
    }
}
