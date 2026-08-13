@php
    $user = auth()->user();
    $isSuperAdmin = $user && method_exists($user, 'hasRole') && $user->hasRole('Super Admin');

    $dbMenus = \App\Models\AdminMenu::active()
        ->parents()
        ->with(['children' => function($q) {
            $q->active()->orderBy('order');
        }])
        ->orderBy('order')
        ->get();

    $filteredMenus = $dbMenus->filter(function($item) use ($user, $isSuperAdmin) {
        if ($isSuperAdmin) {
            return true;
        }

        if (!$user || !method_exists($user, 'hasPermissionTo')) {
            return false;
        }

        $checkPermission = function($permName) use ($user) {
            if (empty($permName)) return false;
            if ($user->hasPermissionTo($permName)) return true;

            if (str_contains($permName, 'orders') && ($user->hasPermissionTo('orders.view') || $user->hasPermissionTo('manage_orders'))) return true;
            if (str_contains($permName, 'quotes') && ($user->hasPermissionTo('quotes.view') || $user->hasPermissionTo('manage_orders'))) return true;
            if ((str_contains($permName, 'products') || str_contains($permName, 'categories')) && ($user->hasPermissionTo('products.view') || $user->hasPermissionTo('manage_products'))) return true;
            if ((str_contains($permName, 'inventory') || str_contains($permName, 'stocks') || str_contains($permName, 'warehouses')) && ($user->hasPermissionTo('inventory.view') || $user->hasPermissionTo('products.view') || $user->hasPermissionTo('manage_products'))) return true;
            if (str_contains($permName, 'customers') && ($user->hasPermissionTo('customers.view') || $user->hasPermissionTo('manage_users'))) return true;
            if (str_contains($permName, 'users') && ($user->hasPermissionTo('users.view') || $user->hasPermissionTo('manage_users'))) return true;
            if ((str_contains($permName, 'roles') || str_contains($permName, 'permissions')) && ($user->hasPermissionTo('roles.view') || $user->hasPermissionTo('manage_roles'))) return true;
            if (str_contains($permName, 'reports') && ($user->hasPermissionTo('reports.view') || $user->hasPermissionTo('view_reports'))) return true;

            return false;
        };

        // Filter submenus by allowed permission
        if ($item->children && $item->children->count() > 0) {
            $visibleChildren = $item->children->filter(function($child) use ($checkPermission) {
                return $checkPermission($child->permission);
            });

            $item->setRelation('children', $visibleChildren);

            if ($visibleChildren->count() > 0) {
                return true;
            }
        }

        // Check single menu item permission
        return $checkPermission($item->permission);
    });

    $navMenuGroups = $filteredMenus->groupBy('section');

    $pendingCustomersCount = \App\Models\User::whereHas('roles', function ($q) {
        $q->where('name', 'User');
    })->where(function ($q) {
        $q->where('status', 'pending')->orWhereNull('status');
    })->count();

    $pendingQuotesCount = \Schema::hasTable('quotes') ? \App\Models\Quote::whereIn('status', ['pending', 'under_review'])->count() : 0;

    $currentUserRole = $user && $user->roles->count() > 0 ? $user->roles->first()->name : 'Staff';
    $userInitials = $user ? strtoupper(substr($user->name, 0, 2)) : 'AD';
@endphp

<!-- Sidebar Navigation Component -->
<aside 
    id="adminSidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200/80 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col justify-between shadow-xs"
>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <!-- Brand Header Logo -->
        <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-200">
                    <i class="fas fa-boxes-packing text-lg"></i>
                </div>
                <div class="sidebar-brand-text flex flex-col mt-4">
                    <span class="font-bold text-slate-900 tracking-tight text-base leading-tight">B2B Wholesale</span>
                    <span class="text-[11px] font-semibold tracking-wider text-indigo-600 uppercase">Enterprise</span>
                </div>
            </a>
            <!-- Mobile Close Button -->
            <button 
                type="button"
                onclick="toggleMobileSidebar()"
                class="lg:hidden text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors"
                aria-label="Close sidebar"
            >
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Wholesale Quick Workspace Badge -->
        <div class="px-4 py-3 mx-3 my-3 bg-slate-50 border border-slate-200/60 rounded-xl flex items-center justify-between sidebar-brand-text">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <div class="flex flex-col">
                    <span class="text-xs font-semibold text-slate-700">Global Hub Store</span>
                    <span class="text-[10px] text-slate-500">Live Inventory Active</span>
                </div>
            </div>
            <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="flex-1 px-3 py-2 space-y-6">
            @foreach ($navMenuGroups as $sectionName => $items)
                <div>
                    @if (!empty($sectionName))
                        <div class="sidebar-brand-text px-3 mb-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            {{ $sectionName }}
                        </div>
                    @endif
                    <ul class="space-y-1">
                        @foreach ($items as $item)
                            @php
                                $hasSubmenu = $item->children && $item->children->count() > 0;
                                $menuId = 'submenu-' . \Illuminate\Support\Str::slug($item->title);
                                
                                $hasActiveChild = false;
                                $parentBadgeCount = 0;
                                if ($hasSubmenu) {
                                    foreach ($item->children as $sub) {
                                        if (!empty($sub->route) && \Illuminate\Support\Facades\Route::has($sub->route)) {
                                            $baseRoute = \Illuminate\Support\Str::before($sub->route, '.index');
                                            if (request()->routeIs($sub->route) || request()->routeIs($baseRoute . '.*')) {
                                                $hasActiveChild = true;
                                            }
                                        }
                                        if (!empty($sub->route) && $sub->route === 'admin.customers.register') {
                                            $parentBadgeCount = $pendingCustomersCount;
                                        }
                                    }
                                }

                                $isActive = !empty($item->route) && \Illuminate\Support\Facades\Route::has($item->route) ? request()->routeIs($item->route) : false;
                                $url = !empty($item->route) && \Illuminate\Support\Facades\Route::has($item->route) ? route($item->route) : ($item->url ?? '#');
                            @endphp

                            <li>
                                @if ($hasSubmenu)
                                    <!-- Parent Menu Button with Submenu -->
                                    <button 
                                        type="button" 
                                        onclick="toggleSubmenu('{{ $menuId }}')"
                                        class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $hasActiveChild ? 'bg-slate-100/80 text-indigo-600 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} group"
                                    >
                                        <div class="flex items-center gap-3">
                                            <i class="{{ $item->icon }} w-5 text-center text-base {{ $hasActiveChild ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                            <span class="nav-item-text">{{ $item->title }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($parentBadgeCount > 0)
                                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white animate-pulse">{{ $parentBadgeCount }}</span>
                                            @endif
                                            <i id="arrow-{{ $menuId }}" class="fas fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ $hasActiveChild ? 'rotate-180' : '' }}"></i>
                                        </div>
                                    </button>

                                    <!-- Submenu Dropdown Container -->
                                    <ul id="{{ $menuId }}" class="{{ $hasActiveChild ? '' : 'hidden' }} pl-4 mt-1 space-y-1 border-l-2 border-slate-100 ml-5">
                                        @foreach ($item->children as $sub)
                                            @php
                                                $isSubActive = !empty($sub->route) && \Illuminate\Support\Facades\Route::has($sub->route) ? request()->routeIs($sub->route) : false;
                                                $subUrl = !empty($sub->route) && \Illuminate\Support\Facades\Route::has($sub->route) ? route($sub->route) : ($sub->url ?? '#');
                                                $isCustomerRegisterRoute = (!empty($sub->route) && $sub->route === 'admin.customers.register');
                                            @endphp
                                            <li>
                                                <a href="{{ $subUrl }}" 
                                                   class="flex items-center justify-between px-3 py-2 rounded-lg font-medium text-xs transition-all duration-150 {{ $isSubActive ? 'text-indigo-600 font-semibold bg-indigo-50/60' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                                                    <div class="flex items-center gap-2.5">
                                                        <i class="{{ $sub->icon ?? 'fas fa-circle' }} text-xs {{ $isSubActive ? 'text-indigo-600' : 'text-slate-400' }}"></i>
                                                        <span class="nav-item-text">{{ $sub->title }}</span>
                                                    </div>
                                                    @if($isCustomerRegisterRoute && $pendingCustomersCount > 0)
                                                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white animate-pulse ml-auto">{{ $pendingCustomersCount }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <!-- Standard Menu Link -->
                                    <a href="{{ $url }}" 
                                       class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-150 {{ $isActive ? 'bg-indigo-50 text-indigo-600 font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <div class="flex items-center gap-3">
                                            <i class="{{ $item->icon }} w-5 text-center text-base {{ $isActive ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}"></i>
                                            <span class="nav-item-text">{{ $item->title }}</span>
                                        </div>
                                        @if(!empty($item->route) && $item->route === 'admin.customers.register' && $pendingCustomersCount > 0)
                                            <span class="px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white animate-pulse">{{ $pendingCustomersCount }}</span>
                                        @endif
                                        @if(($item->title === 'Quotes & Inquiries' || (!empty($item->route) && str_contains($item->route, 'quote'))) && $pendingQuotesCount > 0)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-xs animate-pulse" title="{{ $pendingQuotesCount }} pending quotes">{{ $pendingQuotesCount }}</span>
                                        @endif
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>

    <!-- Bottom User Profile Footer Card -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/50">
        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-slate-100/80 transition-colors">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm shadow-xs shrink-0">
                    {{ $userInitials }}
                </div>
                <div class="sidebar-brand-text truncate">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'Admin User' }}</p>
                    <p class="text-[11px] text-slate-500 truncate"><span class="font-semibold text-indigo-600">{{ $currentUserRole }}</span> • {{ auth()->user()->email ?? 'user@wholesale.com' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                @csrf
                <button type="submit" class="sidebar-brand-text text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-slate-200/50 transition-colors cursor-pointer" title="Log Out">
                    <i class="fas fa-arrow-right-from-bracket text-sm"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    function toggleSubmenu(menuId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById('arrow-' + menuId);
        if (menu) {
            menu.classList.toggle('hidden');
        }
        if (arrow) {
            arrow.classList.toggle('rotate-180');
        }
    }
</script>
