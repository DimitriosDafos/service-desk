<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Tenancy\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTenantAdmin()) {
                abort(403, 'Only tenant admins can manage groups.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }

    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $groups = Group::where('tenant_id', $tenantId)->with('users')->get();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $group = Group::create($validated);

        return redirect()->route('tenants.groups.show', $group)->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        $this->authorizeTenant($group);
        $group->load(['users', 'queues']);
        return view('groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $this->authorizeTenant($group);
        return view('groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $this->authorizeTenant($group);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $group->update($validated);

        return redirect()->route('tenants.groups.show', $group)->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        $this->authorizeTenant($group);
        $group->delete();
        return redirect()->route('tenants.groups.index')->with('success', 'Group deleted successfully');
    }

    public function addUser(Request $request, Group $group)
    {
        $this->authorizeTenant($group);

        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        $group->users()->syncWithoutDetaching([$validated['user_id']]);

        return back()->with('success', 'User added to group');
    }

    public function removeUser(Request $request, Group $group)
    {
        $this->authorizeTenant($group);

        $validated = $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        $group->users()->detach($validated['user_id']);

        return back()->with('success', 'User removed from group');
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
