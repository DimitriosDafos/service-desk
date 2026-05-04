<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Tenancy\Models\Queue;
use App\SLA\Models\SlaPolicy;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTenantAdmin()) {
                abort(403, 'Only tenant admins can manage queues.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $queues = Queue::where('tenant_id', $tenantId)->with('group', 'slaPolicy')->get();
        return view('queues.index', compact('queues'));
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;
        $groups = \App\Tenancy\Models\Group::where('tenant_id', $tenantId)->get();
        $slaPolicies = SlaPolicy::where('tenant_id', $tenantId)->get();
        return view('queues.create', compact('groups', 'slaPolicies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'group_id' => 'nullable|uuid|exists:groups,id',
            'sla_policy_id' => 'nullable|uuid|exists:sla_policies,id',
            'auto_assign' => 'boolean',
            'assignment_strategy' => 'string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $queue = Queue::create($validated);

        return redirect()->route('tenants.queues.show', $queue)->with('success', 'Queue created successfully');
    }

    public function show(Queue $queue)
    {
        $this->authorizeTenant($queue);
        $queue->load(['group', 'slaPolicy', 'tickets']);
        return view('queues.show', compact('queue'));
    }

    public function edit(Queue $queue)
    {
        $this->authorizeTenant($queue);
        $tenantId = auth()->user()->tenant_id;
        $groups = \App\Tenancy\Models\Group::where('tenant_id', $tenantId)->get();
        $slaPolicies = SlaPolicy::where('tenant_id', $tenantId)->get();
        return view('queues.edit', compact('queue', 'groups', 'slaPolicies'));
    }

    public function update(Request $request, Queue $queue)
    {
        $this->authorizeTenant($queue);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'group_id' => 'nullable|uuid|exists:groups,id',
            'sla_policy_id' => 'nullable|uuid|exists:sla_policies,id',
            'auto_assign' => 'boolean',
            'assignment_strategy' => 'string',
            'is_active' => 'boolean',
        ]);

        $queue->update($validated);

        return redirect()->route('tenants.queues.show', $queue)->with('success', 'Queue updated successfully');
    }

    public function destroy(Queue $queue)
    {
        $this->authorizeTenant($queue);
        $queue->delete();
        return redirect()->route('tenants.queues.index')->with('success', 'Queue deleted successfully');
    }

    protected function authorizeTenant($model)
    {
        if (auth()->user()->isSystemLevel()) {
            return;
        }

        if ($model->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
    }
}
