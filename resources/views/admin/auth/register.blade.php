<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Account Registration - B2B Wholesale Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets / Tailwind CSS with CDN Fallback -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-slate-100 flex items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden selection:bg-indigo-500 selection:text-white">

    <!-- Ambient Glowing Backdrop Effects -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 my-8">
        
        <!-- Logo & Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-xl shadow-indigo-500/20 mb-4 group hover:scale-105 transition-transform duration-300">
                <i class="fas fa-user-plus text-2xl group-hover:rotate-6 transition-transform duration-300"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
                Create Account
            </h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">
                Register for access to the Wholesale Portal
            </p>
        </div>

        <!-- Registration Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/50 relative">
            
            <!-- Instant Access Notice Pill -->
            <div class="mb-6 p-3.5 bg-emerald-950/60 border border-emerald-800/50 rounded-2xl flex items-start gap-3">
                <div class="p-2 bg-emerald-600/20 text-emerald-400 rounded-xl text-xs flex-shrink-0 mt-0.5">
                    <i class="fas fa-circle-check"></i>
                </div>
                <div class="text-xs text-slate-300 flex-1">
                    <p class="font-semibold text-emerald-300 mb-0.5">Instant Access:</p>
                    <p class="text-[11px] text-slate-400">
                        Create your account to start browsing our wholesale catalog, manage orders, and request bulk quotes immediately.
                    </p>
                </div>
            </div>

            <!-- Global Error Banner -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 bg-rose-950/60 border border-rose-800/50 rounded-2xl text-xs text-rose-300 flex items-start gap-2.5">
                    <i class="fas fa-triangle-exclamation text-sm text-rose-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Registration Errors</p>
                        <ul class="list-disc list-inside mt-1 space-y-0.5 text-rose-300/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.store') }}" class="space-y-4">
                @csrf

                <!-- Full Name Input -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Full Name
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="far fa-user text-sm"></i>
                        </div>
                        <input 
                            id="name" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            placeholder="John Doe"
                            class="w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                        >
                    </div>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="far fa-envelope text-sm"></i>
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            placeholder="user@example.com"
                            class="w-full pl-10 pr-4 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="far fa-lock text-sm"></i>
                        </div>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Minimum 8 characters"
                            class="w-full pl-10 pr-10 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                        >
                        <button 
                            type="button" 
                            onclick="toggleVisibility('password', 'passwordIcon')"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors"
                        >
                            <i id="passwordIcon" class="far fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400 font-medium flex items-center gap-1">
                            <i class="fas fa-circle-exclamation text-[10px]"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="far fa-lock text-sm"></i>
                        </div>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            placeholder="Re-enter password"
                            class="w-full pl-10 pr-10 py-3 bg-slate-950/60 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/40 active:scale-[0.99] transition-all duration-200 flex items-center justify-center gap-2 group cursor-pointer"
                >
                    <span>Submit Registration</span>
                    <i class="fas fa-paper-plane text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>

            </form>

            <div class="mt-6 pt-5 border-t border-slate-800/80 text-center">
                <div class="text-xs text-slate-400">
                    <span>Already have an account?</span> 
                    <a href="{{ route('admin.login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 underline underline-offset-4 transition-colors">
                        Sign In here
                    </a>
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <p class="text-center text-xs text-slate-500 mt-6">
            &copy; {{ date('Y') }} Wholesale Portal. Secure Admin Gateway.
        </p>

    </div>

    <!-- Password Visibility Toggle Script -->
    <script>
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
