<?php

namespace App\Http\Controllers\SLA;

use App\Http\Controllers\Controller;
use App\SLA\Models\SlaPolicy;
use App\SLA\Models\BusinessHours;
use Illuminate\Http\Request;

class SlaPolicyController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTenantAdmin()) {
                abort(403, 'Only tenant admins can manage SLA policies.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $policies = SlaPolicy::where('tenant_id', $tenantId)->with('businessHours')->get();
        return view('sla.policies.index', compact('policies'));
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;
        $businessHours = BusinessHours::where('tenant_id', $tenantId)->get();
        return view('sla.policies.create', compact('businessHours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:critical,high,medium,low',
            'response_time_minutes' => 'nullable|integer|min:0',
            'resolution_time_minutes' => 'nullable|integer|min:0',
            'business_hours_id' => 'nullable|uuid|exists:business_hours,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $policy = SlaPolicy::create($validated);

        return redirect()->route('sla.policies.show', $policy)->with('success', 'SLA Policy created successfully');
    }

    public function show(SlaPolicy $policy)
    {
        $this->authorizePolicy($policy);
        $policy->load('businessHours', 'queues');
        return view('sla.policies.show', compact('policy'));
    }

    public function edit(SlaPolicy $policy)
    {
        $this->authorizePolicy($policy);
        $tenantId = auth()->user()->tenant_id;
        $businessHours = BusinessHours::where('tenant_id', $tenantId)->get();
        return view('sla.policies.edit', compact('policy', 'businessHours'));
    }

    public function update(Request $request, SlaPolicy $policy)
    {
        $this->authorizePolicy($policy);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:critical,high,medium,low',
            'response_time_minutes' => 'nullable|integer|min:0',
            'resolution_time_minutes' => 'nullable|integer|min:0',
            'business_hours_id' => 'nullable|uuid|exists:business_hours,id',
            'is_active' => 'boolean',
        ]);

        $policy->update($validated);

        return redirect()->route('sla.policies.show', $policy)->with('success', 'SLA Policy updated successfully');
    }

    public function destroy(SlaPolicy $policy)
    {
        $this->authorizePolicy($policy);
        $policy->delete();
        return redirect()->route('sla.policies.index')->with('success', 'SLA Policy deleted successfully');
    }

    protected function authorizePolicy($policy)
    {
        $user = auth()->user();
        
        if ($user->isSystemLevel()) {
            return;
        }

        if ($policy->tenant_id !== $user->tenant_id) {
            abort(403);
        }
    }
}
