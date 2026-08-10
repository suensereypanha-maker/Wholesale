<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses.
     */
    public function index(Request $request): View
    {
        $query = Warehouse::withCount('stocks');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $warehouses = $query->latest()->get();

        // Calculate summary metrics
        $totalWarehouses = Warehouse::count();
        $activeWarehouses = Warehouse::where('status', 'active')->count();
        $totalCapacity = Warehouse::sum('capacity');

        return view('admin.warehouses.index', compact(
            'warehouses',
            'totalWarehouses',
            'activeWarehouses',
            'totalCapacity'
        ));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create(): View
    {
        $types = ['Distribution Center', 'Regional Hub', 'Cold Storage', 'Bulk Depot', 'Fulfillment Center'];
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'];

        return view('admin.warehouses.create', compact('types', 'statuses'));
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:warehouses,code',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:500',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:active,inactive,maintenance',
            'notes' => 'nullable|string|max:2000',
        ]);

        $warehouse = Warehouse::create($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', "Warehouse '{$warehouse->name}' ({$warehouse->code}) created successfully.");
    }

    /**
     * Display the specified warehouse with its inventory.
     */
    public function show(Warehouse $warehouse): View
    {
        $warehouse->load(['stocks']);

        $stocksQuery = $warehouse->stocks();
        $stocks = $stocksQuery->latest()->get();

        return view('admin.warehouses.show', compact('warehouse', 'stocks'));
    }

    /**
     * Show the form for editing the specified warehouse.
     */
    public function edit(Warehouse $warehouse): View
    {
        $types = ['Distribution Center', 'Regional Hub', 'Cold Storage', 'Bulk Depot', 'Fulfillment Center'];
        $statuses = ['active' => 'Active', 'inactive' => 'Inactive', 'maintenance' => 'Maintenance'];

        return view('admin.warehouses.edit', compact('warehouse', 'types', 'statuses'));
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('warehouses')->ignore($warehouse->id)],
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:500',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|string|in:active,inactive,maintenance',
            'notes' => 'nullable|string|max:2000',
        ]);

        $warehouse->update($validated);

        return redirect()->route('admin.warehouses.index')
            ->with('success', "Warehouse '{$warehouse->name}' updated successfully.");
    }

    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy(Warehouse $warehouse): RedirectResponse
    {
        if ($warehouse->stocks()->count() > 0) {
            return back()->with('error', "Cannot delete warehouse '{$warehouse->name}' because it contains active stock items.");
        }

        $warehouseName = $warehouse->name;
        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')
            ->with('success', "Warehouse '{$warehouseName}' deleted successfully.");
    }
}
