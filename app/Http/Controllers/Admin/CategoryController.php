<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request): View
    {
        $query = Category::query();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $categories = $query->orderBy('order', 'asc')->orderBy('name', 'asc')->paginate(12)->withQueryString();

        // Calculate metrics
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', 'active')->count();
        $typesCount = Category::distinct('type')->whereNotNull('type')->count('type');

        $types = Category::distinct()
            ->whereNotNull('type')
            ->pluck('type')
            ->filter();

        return view('admin.categories.index', compact(
            'categories',
            'totalCategories',
            'activeCategories',
            'typesCount',
            'types'
        ));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $types = [
            'Computer Hardware',
            'Laptop Material',
            'Thermal Solution',
            'Networking',
            'Accessories',
            'Peripherals',
            'General Material',
        ];

        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];

        $icons = [
            'fas fa-microchip' => 'Microchip / CPU',
            'fas fa-memory' => 'RAM / Memory',
            'fas fa-hard-drive' => 'SSD / Storage Drive',
            'fas fa-display' => 'Display / GPU',
            'fas fa-circuit-board' => 'Motherboard',
            'fas fa-bolt' => 'Power Supply / PSU',
            'fas fa-laptop' => 'Laptop Component',
            'fas fa-fan' => 'Cooling Fan',
            'fas fa-network-wired' => 'Networking Gear',
            'fas fa-keyboard' => 'Peripheral / Keyboard',
            'fas fa-plug' => 'Cables & Adapters',
            'fas fa-layer-group' => 'General Material',
        ];

        // Suggest code
        $nextId = (Category::max('id') ?? 0) + 1;
        $suggestedCode = 'CAT-' . str_pad($nextId + 100, 3, '0', STR_PAD_LEFT);

        return view('admin.categories.create', compact('types', 'statuses', 'icons', 'suggestedCode'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:categories,code',
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'status' => 'required|string|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category = Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Supply Category '{$category->name}' ({$category->code}) created successfully.");
    }

    /**
     * Display the specified category details.
     */
    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category): View
    {
        $types = [
            'Computer Hardware',
            'Laptop Material',
            'Thermal Solution',
            'Networking',
            'Accessories',
            'Peripherals',
            'General Material',
        ];

        $statuses = [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ];

        $icons = [
            'fas fa-microchip' => 'Microchip / CPU',
            'fas fa-memory' => 'RAM / Memory',
            'fas fa-hard-drive' => 'SSD / Storage Drive',
            'fas fa-display' => 'Display / GPU',
            'fas fa-circuit-board' => 'Motherboard',
            'fas fa-bolt' => 'Power Supply / PSU',
            'fas fa-laptop' => 'Laptop Component',
            'fas fa-fan' => 'Cooling Fan',
            'fas fa-network-wired' => 'Networking Gear',
            'fas fa-keyboard' => 'Peripheral / Keyboard',
            'fas fa-plug' => 'Cables & Adapters',
            'fas fa-layer-group' => 'General Material',
        ];

        return view('admin.categories.edit', compact('category', 'types', 'statuses', 'icons'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('categories')->ignore($category->id)],
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string|max:1000',
            'icon' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'status' => 'required|string|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', "Supply Category '{$category->name}' updated successfully.");
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Supply Category '{$categoryName}' deleted successfully.");
    }
}
