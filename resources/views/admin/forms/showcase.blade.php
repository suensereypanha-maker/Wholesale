@extends('admin.layout.app')

@section('title', 'Form Components Showcase')

@section('content')
<div class="space-y-8 w-full max-w-7xl mx-auto">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3.5">
            <span class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-cubes-stacked text-2xl"></i>
            </span>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Form Components Suite</h1>
                    <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                        Ready to Use
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Simple, clean, and developer-friendly Blade form components with automatic validation & error handling</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="#code-cheatsheet" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                <i class="fas fa-code text-xs mr-1.5"></i> Syntax Reference
            </a>
        </div>
    </div>

    <!-- Quick Features Highlights -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fas fa-wand-magic-sparkles"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Auto Validation</h4>
                <p class="text-[11px] text-slate-400">Shows @error messages automatically</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fas fa-rotate-left"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Old Value Retention</h4>
                <p class="text-[11px] text-slate-400">Remembers input state on fail</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fas fa-icons"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Icon Support</h4>
                <p class="text-[11px] text-slate-400">Built-in prefix & suffix icons</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200/80 shadow-2xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm shrink-0">
                <i class="fas fa-feather"></i>
            </div>
            <div>
                <h4 class="text-xs font-bold text-slate-800">Dual Syntax</h4>
                <p class="text-[11px] text-slate-400">&lt;x-input&gt; or &lt;x-forms.input&gt;</p>
            </div>
        </div>
    </div>

    <!-- Main Live Interactive Demo Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Column 1: Live Interactive Test Form -->
        <div class="lg:col-span-7 space-y-6">
            <x-forms.card 
                title="Live Test Form" 
                description="Test field validation, focus rings, icons, and error responses live" 
                icon="fas fa-vial-circle-check"
            >
                <x-forms.form action="{{ route('admin.forms.demo.submit') }}" method="POST">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-forms.input 
                            name="demo_name" 
                            label="Full Name" 
                            placeholder="e.g. John Doe" 
                            icon="fas fa-user" 
                            required 
                            helpText="Required, min 3 characters"
                        />

                        <x-forms.input 
                            type="email" 
                            name="demo_email" 
                            label="Email Address" 
                            placeholder="john@example.com" 
                            icon="fas fa-envelope" 
                            required 
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-forms.select 
                            name="demo_role" 
                            label="Account Role" 
                            placeholder="-- Select User Role --"
                            :options="['admin' => 'Administrator', 'manager' => 'Store Manager', 'customer' => 'Wholesale Customer']" 
                            icon="fas fa-user-shield" 
                            required
                        />

                        <x-forms.input 
                            type="password" 
                            name="demo_password" 
                            label="Password" 
                            placeholder="••••••••" 
                            icon="fas fa-lock" 
                        />
                    </div>

                    <x-forms.textarea 
                        name="demo_bio" 
                        label="User Biography / Notes" 
                        placeholder="Write a short summary about this account..." 
                        icon="fas fa-align-left" 
                        rows="3" 
                    />

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-3">
                        <x-forms.switch 
                            name="demo_active" 
                            label="Account Active Status" 
                            description="Enable or disable user access to the admin backend"
                            :checked="true"
                        />

                        <div class="border-t border-slate-200/60 pt-2"></div>

                        <x-forms.checkbox 
                            name="demo_terms" 
                            label="I accept terms and conditions" 
                            description="Required to test validation failure when left unchecked"
                        />
                    </div>

                    <x-forms.file 
                        name="demo_avatar" 
                        label="Profile Avatar (Optional)" 
                        accept="image/png, image/jpeg" 
                        helpText="Upload PNG or JPG image max 2MB"
                    />

                    <x-slot:footer>
                        <x-forms.button type="button" variant="outline" onclick="window.location.reload()">
                            Reset
                        </x-forms.button>
                        <x-forms.button type="submit" variant="primary" icon="fas fa-paper-plane">
                            Submit & Test Validation
                        </x-forms.button>
                    </x-slot:footer>
                </x-forms.form>
            </x-forms.card>
        </div>

        <!-- Column 2: Button Showcase & UI Variants -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Button Variants Card -->
            <x-forms.card title="Button Component Variants" description="Pre-styled action buttons" icon="fas fa-toggle-on">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-2">Color Variants</p>
                        <div class="flex flex-wrap gap-2">
                            <x-forms.button variant="primary">Primary</x-forms.button>
                            <x-forms.button variant="secondary">Secondary</x-forms.button>
                            <x-forms.button variant="success" icon="fas fa-check">Success</x-forms.button>
                            <x-forms.button variant="danger" icon="fas fa-trash">Danger</x-forms.button>
                            <x-forms.button variant="warning">Warning</x-forms.button>
                            <x-forms.button variant="dark">Dark</x-forms.button>
                            <x-forms.button variant="outline">Outline</x-forms.button>
                            <x-forms.button variant="ghost">Ghost</x-forms.button>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-2">Sizes & Icon Placement</p>
                        <div class="flex flex-wrap items-center gap-2">
                            <x-forms.button size="sm" variant="primary" icon="fas fa-plus">Small</x-forms.button>
                            <x-forms.button size="md" variant="primary" icon="fas fa-arrow-right" iconPosition="right">Medium</x-forms.button>
                            <x-forms.button size="lg" variant="dark" icon="fas fa-star">Large</x-forms.button>
                        </div>
                    </div>
                </div>
            </x-forms.card>

            <!-- Toggles & Checkbox Showcase -->
            <x-forms.card title="Toggles & Options" description="Switch and radio component previews" icon="fas fa-sliders">
                <div class="space-y-4">
                    <x-forms.switch name="sw_indigo" label="Indigo Toggle" color="indigo" :checked="true" />
                    <x-forms.switch name="sw_emerald" label="Emerald Toggle" color="emerald" :checked="true" />
                    <x-forms.switch name="sw_rose" label="Rose Alert Toggle" color="rose" :checked="true" />
                    
                    <div class="pt-2 border-t border-slate-100 space-y-2">
                        <p class="text-xs font-semibold text-slate-500">Radio Options</p>
                        <x-forms.radio name="plan_type" value="starter" label="Starter Plan" description="Basic access for small teams" :checked="true" />
                        <x-forms.radio name="plan_type" value="enterprise" label="Enterprise Plan" description="Unlimited bandwidth and full admin controls" />
                    </div>
                </div>
            </x-forms.card>
            <!-- Permission Protection Showcase Card -->
            <x-forms.card title="RBAC Permission Control" description="Built-in permission & role checking on any component" icon="fas fa-lock flex-shrink-0">
                <div class="space-y-4">
                    <p class="text-xs text-slate-500">
                        Add <code class="text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded">permission="manage_users"</code> or <code class="text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded">role="Super Admin"</code> to any input or button to automatically guard it.
                    </p>

                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Interactive Test Buttons</p>
                        
                        <div class="flex flex-wrap gap-2">
                            <!-- Super Admin / Has Permission Button -->
                            <x-forms.button permission="manage_users" variant="success" size="sm" icon="fas fa-shield-check">
                                Permission Granted (manage_users)
                            </x-forms.button>

                            <!-- Restricted Action Button (Behavior: Disable) -->
                            <x-forms.button permission="restricted_action_xyz" permissionBehavior="disable" variant="danger" size="sm" icon="fas fa-ban">
                                Restricted Action (Disabled)
                            </x-forms.button>
                        </div>
                    </div>
                </div>
            </x-forms.card>
        </div>

    </div>

    <!-- Code Examples & Developer Cheat Sheet -->
    <div id="code-cheatsheet" class="bg-slate-900 text-slate-100 p-6 md:p-8 rounded-2xl shadow-lg space-y-6">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-indigo-500/20 text-indigo-400 rounded-xl border border-indigo-500/30">
                    <i class="fas fa-code text-lg"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-white">Developer Syntax Cheat Sheet</h3>
                    <p class="text-xs text-slate-400">Copy & paste these clean component tags into any Blade view inside resources/views/admin/</p>
                </div>
            </div>
            <span class="text-xs text-indigo-400 font-mono font-bold bg-indigo-950/60 px-3 py-1 rounded-lg border border-indigo-800/50">
                resources/views/components/forms/
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-mono text-xs">
            
            <!-- Text & Email Inputs -->
            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/80 space-y-2">
                <span class="text-indigo-400 font-bold font-sans text-xs"># Text / Email Input</span>
                <pre class="text-slate-300 overflow-x-auto"><code>&lt;x-forms.input 
    name="email" 
    label="Email Address" 
    placeholder="user@example.com" 
    icon="fas fa-envelope" 
    required 
/&gt;</code></pre>
            </div>

            <!-- Select Dropdown -->
            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/80 space-y-2">
                <span class="text-indigo-400 font-bold font-sans text-xs"># Select Dropdown</span>
                <pre class="text-slate-300 overflow-x-auto"><code>&lt;x-forms.select 
    name="role_id" 
    label="Select Role" 
    :options="$roles" 
    icon="fas fa-shield" 
/&gt;</code></pre>
            </div>

            <!-- Toggle Switch -->
            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/80 space-y-2">
                <span class="text-indigo-400 font-bold font-sans text-xs"># Toggle Switch</span>
                <pre class="text-slate-300 overflow-x-auto"><code>&lt;x-forms.switch 
    name="is_active" 
    label="Active Account" 
    :checked="$user-&gt;is_active" 
    description="Allow login access" 
/&gt;</code></pre>
            </div>

            <!-- Permission Protected Button -->
            <div class="bg-slate-950 p-4 rounded-xl border border-slate-800/80 space-y-2">
                <span class="text-indigo-400 font-bold font-sans text-xs"># Permission Protected Button</span>
                <pre class="text-slate-300 overflow-x-auto"><code>&lt;x-forms.button 
    permission="manage_users" 
    variant="primary" 
    icon="fas fa-user-plus"&gt;
    Add New User
&lt;/x-forms.button&gt;</code></pre>
            </div>

        </div>
    </div>

</div>
@endsection
