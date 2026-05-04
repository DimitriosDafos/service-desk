<?php

namespace App\Http\Controllers\CMDB;

use App\Http\Controllers\Controller;
use App\CMDB\Models\Asset;
use App\CMDB\Models\AssetType;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        $query = Asset::where('tenant_id', $tenantId);

        if ($request->type_id) {
            $query->where('asset_type_id', $request->type_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

        $assets = $query->with('assetType', 'assignedUser')->paginate(20);
        $types = AssetType::where('tenant_id', $tenantId)->get();

        return view('assets.index', compact('assets', 'types'));
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;
        $types = AssetType::where('tenant_id', $tenantId)->get();
        $users = \App\Models\User::where('tenant_id', $tenantId)->get();
        return view('assets.create', compact('types', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type_id' => 'nullable|uuid|exists:asset_types,id',
            'serial_number' => 'nullable|string',
            'asset_tag' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,retired',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string',
            'assigned_user_id' => 'nullable|uuid|exists:users,id',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;

        $asset = Asset::create($validated);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset created successfully');
    }

    public function show(Asset $asset)
    {
        $this->authorizeAsset($asset);
        $asset->load('assetType', 'assignedUser', 'parentAssets', 'childAssets', 'tickets');
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $this->authorizeAsset($asset);
        $tenantId = auth()->user()->tenant_id;
        $types = AssetType::where('tenant_id', $tenantId)->get();
        $users = \App\Models\User::where('tenant_id', $tenantId)->get();
        return view('assets.edit', compact('asset', 'types', 'users'));
    }

    public function update(Request $request, Asset $asset)
    {
        $this->authorizeAsset($asset);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type_id' => 'nullable|uuid|exists:asset_types,id',
            'serial_number' => 'nullable|string',
            'asset_tag' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance,retired',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'location' => 'nullable|string',
            'assigned_user_id' => 'nullable|uuid|exists:users,id',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset updated successfully');
    }

    public function destroy(Asset $asset)
    {
        $this->authorizeAsset($asset);
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully');
    }

    protected function authorizeAsset($asset)
    {
        $user = auth()->user();
        
        if ($user->isSystemLevel()) {
            return;
        }

        if ($asset->tenant_id !== $user->tenant_id) {
            abort(403);
        }
    }
}
