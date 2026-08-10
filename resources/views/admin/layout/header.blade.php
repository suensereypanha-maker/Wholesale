<!-- Top Header Navbar Component -->
<header class="sticky top-0 z-30 h-16 bg-white/90 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 flex items-center justify-between shadow-2xs">

    <!-- Left Header Controls: Sidebar Toggles & Global Search -->
    <div class="flex items-center gap-3 sm:gap-4 flex-1">
        <!-- Mobile Hamburger Toggle -->
        <button 
            type="button" 
            onclick="toggleMobileSidebar()"
            class="lg:hidden text-slate-500 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 transition-colors"
            aria-label="Toggle navigation menu"
        >
            <i class="fas fa-bars text-lg"></i>
        </button>

        <!-- Desktop Sidebar Toggle -->
        <button 
            type="button" 
            onclick="toggleDesktopSidebar()"
            class="hidden lg:flex text-slate-500 hover:text-slate-800 p-2 rounded-xl hover:bg-slate-100 transition-colors"
            aria-label="Collapse sidebar"
        >
            <i class="fas fa-bars-staggered text-base"></i>
        </button>

        <!-- Search Bar -->
        <div class="relative max-w-md w-full hidden sm:block">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fas fa-search text-sm"></i>
            </div>
            <input 
                type="text" 
                placeholder="Search bulk orders, wholesale SKUs, B2B clients..." 
                class="w-full pl-10 pr-16 py-2 bg-slate-100/80 border border-transparent rounded-xl text-sm placeholder-slate-400 text-slate-800 focus:bg-white focus:border-indigo-500 focus:outline-hidden focus:ring-3 focus:ring-indigo-500/15 transition-all"
            >
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-200/60 rounded border border-slate-300/50">⌘K</span>
            </div>
        </div>
    </div>

    <!-- Right Header Controls: Actions, Currency, Notifications, Profile -->
    <div class="flex items-center gap-2 sm:gap-4">

        <!-- Quick Action Button -->
        <div class="relative hidden md:block">
            <button 
                type="button"
                class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-indigo-600/20 transition-all duration-150 active:scale-95"
            >
                <i class="fas fa-plus text-xs"></i>
                <span>New Wholesale Order</span>
            </button>
        </div>

        <!-- Currency & Region Selector -->
        <div class="hidden xl:flex items-center gap-1.5 px-3 py-1.5 bg-slate-100/70 border border-slate-200/60 rounded-xl text-xs font-medium text-slate-600">
            <i class="fas fa-globe text-slate-400"></i>
            <span>USD ($)</span>
            <span class="text-slate-300">|</span>
            <span class="text-emerald-600 font-semibold">Tier A Pricing</span>
        </div>

        <!-- Notifications Bell -->
        <div class="relative">
            <button 
                type="button"
                class="relative text-slate-500 hover:text-slate-800 p-2.5 rounded-xl hover:bg-slate-100 transition-colors"
                aria-label="View notifications"
            >
                <i class="far fa-bell text-base"></i>
                <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
            </button>
        </div>

        <!-- Quick Support / Help Icon -->
        <button 
            type="button"
            class="text-slate-500 hover:text-slate-800 p-2.5 rounded-xl hover:bg-slate-100 transition-colors hidden sm:block"
            title="Help & Documentation"
        >
            <i class="far fa-circle-question text-base"></i>
        </button>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- User Profile Slide Down Dropdown Menu -->
        <div class="relative pl-1" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
            <!-- Profile Toggle Button -->
            <button 
                type="button" 
                @click="profileOpen = !profileOpen"
                class="flex items-center gap-3 p-1.5 rounded-2xl hover:bg-slate-100/80 border border-transparent hover:border-slate-200/80 transition-all duration-200 focus:outline-hidden cursor-pointer group"
                aria-expanded="false"
                :aria-expanded="profileOpen.toString()"
            >
                <div class="relative">
                    <div class="w-9 h-9 rounded-xl bg-slate-900 text-white font-semibold flex items-center justify-center text-sm shadow-xs border border-slate-700 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="hidden lg:flex flex-col text-left">
                    <span class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                    <span class="text-[11px] text-slate-500 leading-tight">{{ auth()->user()->email ?? 'admin@wholesale.com' }}</span>
                </div>
                <i class="fas fa-chevron-down text-slate-400 group-hover:text-slate-600 text-xs transition-transform duration-200 ml-0.5" :class="profileOpen ? 'rotate-180 text-indigo-600' : ''"></i>
            </button>

            <!-- Slide Down Dropdown Panel -->
            <div 
                x-show="profileOpen" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                class="absolute right-0 mt-2 w-64 bg-white backdrop-blur-md rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 z-50 overflow-hidden divide-y divide-slate-100/80"
                style="display: none;"
            >
                <!-- User Profile Summary Header -->
                <div class="p-3.5 bg-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-base shadow-xs border border-slate-700 shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Admin', 0, 2)) }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                        <span class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'admin@wholesale.com' }}</span>
                        <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-700 bg-emerald-100/70 rounded-md w-fit">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Online Active
                        </span>
                    </div>
                </div>

                <!-- Action Items Group -->
                <div class="p-1.5 space-y-0.5">
                    <!-- Clear Cache Button -->
                    <form method="POST" action="{{ route('admin.clear-cache') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-medium text-slate-700 hover:text-amber-700 hover:bg-amber-50/80 transition-all duration-150 group cursor-pointer"
                        >
                            <div class="w-7 h-7 rounded-lg bg-white text-amber-600 group-hover:bg-amber-500 group-hover:text-white flex items-center justify-center transition-colors">
                                <i class="fas fa-broom text-xs"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="font-semibold leading-snug">Clear Cache</span>
                                <span class="text-[10px] text-slate-400 group-hover:text-amber-600/80">Purge views, routes & system cache</span>
                            </div>
                        </button>
                    </form>
                </div>

                <!-- Logout Item -->
                <div class="p-1.5">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-700 hover:text-rose-700 hover:bg-rose-50/80 transition-all duration-150 group cursor-pointer"
                        >
                            <div class="w-7 h-7 rounded-lg bg-white text-rose-600 group-hover:bg-rose-500 group-hover:text-white flex items-center justify-center transition-colors">
                                <i class="fas fa-arrow-right-from-bracket text-xs"></i>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="font-semibold leading-snug">Log Out</span>
                                <span class="text-[10px] text-slate-400 group-hover:text-rose-600/80">End active session securely</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
