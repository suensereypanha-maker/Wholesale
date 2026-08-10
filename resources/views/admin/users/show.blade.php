@extends('admin.layout.app')

@section('title', 'User Profile - ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to User List</span>
        </a>

        <div class="flex items-center gap-3">
            <x-forms.button 
                href="{{ route('admin.users.edit', $user) }}" 
                variant="outline" 
                icon="fas fa-pen-to-square"
                permission="manage_users"
            >
                Edit Account
            </x-forms.button>
        </div>
    </div>

    <!-- Main Profile Card -->
    <x-forms.card title="User Account Overview" description="Detailed profile parameters and security permissions" icon="fas fa-id-card">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 pb-6">
            <!-- Avatar Icon -->
            <div class="w-20 h-20 rounded-2xl bg-slate-900 text-white font-black flex items-center justify-center text-xl shadow-md border-2 border-slate-700 shrink-0">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>

            <div class="space-y-2 text-center sm:text-left flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 self-center sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Active Status
                    </span>
                </div>

                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                    @forelse ($user->roles as $role)
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 flex items-center gap-1.5">
                            <i class="fas fa-shield-halved text-xs"></i>
                            <span>{{ $role->name }}</span>
                        </span>
                    @empty
                        <span class="text-slate-400 italic text-xs">No assigned role</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Account ID</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">#{{ $user->id }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Created Date</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->created_at ? $user->created_at->format('M d, Y - H:i') : 'N/A' }}</p>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                <p class="text-[11px] font-bold text-slate-400 uppercase">Last Updated</p>
                <p class="text-sm font-extrabold text-slate-800 mt-1">{{ $user->updated_at ? $user->updated_at->format('M d, Y - H:i') : 'N/A' }}</p>
            </div>
        </div>

    </x-forms.card>

</div>
@endsection
