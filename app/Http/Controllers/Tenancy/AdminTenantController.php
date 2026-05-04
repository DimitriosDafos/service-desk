<?php

namespace App\Http\Controllers\Tenancy;

use App\Http\Controllers\Controller;
use App\Tenancy\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminTenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount('users', 'tickets')->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tenant = Tenant::create($validated);

        $adminEmail = 'admin@' . $validated['slug'] . '.com';
        
        $adminPassword = Str::random(10);
        
        $admin = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Admin',
            'email' => $adminEmail,
            'password' => Hash::make($adminPassword),
            'role' => 'tenant_admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.tenants.show', $tenant)->with('success', 'Tenant created successfully')
            ->with('admin_credentials', [
                'email' => $adminEmail,
                'password' => $adminPassword
            ]);
    }

    public function show(Tenant $tenant)
    {
        $tenant->loadCount('users', 'tickets');
        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $tenant->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tenant->update($validated);

        return redirect()->route('admin.tenants.show', $tenant)->with('success', 'Tenant updated successfully');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully');
    }
}
