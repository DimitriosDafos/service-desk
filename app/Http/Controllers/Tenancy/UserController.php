<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isTenantAdmin()) {
                abort(403, 'Only tenant admins can manage users.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $tenantId = auth()->user()->tenant_id;
        $users = User::where('tenant_id', $tenantId)->get();
        return view('tenants.users.index', compact('users'));
    }

    public function create()
    {
        return view('tenants.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:agent,requester,auditor',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('tenants.users.index')->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $this->authorizeUser($user);
        return view('tenants.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorizeUser($user);
        return view('tenants.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeUser($user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:agent,requester,auditor',
            'phone' => 'nullable|string',
            'department' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('tenants.users.show', $user)->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $this->authorizeUser($user);
        
        if ($user->id === auth()->user()->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }
        
        $user->delete();
        return redirect()->route('tenants.users.index')->with('success', 'User deleted successfully');
    }

    protected function authorizeUser($user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }
    }
}
