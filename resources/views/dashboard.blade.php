<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="font-family:'Merriweather',serif; color: var(--muni-red-dark);">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-sm border-t-4 p-6" style="border-color: var(--muni-gold);">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/images/muni-logo.png') }}" alt="Muni Logo" class="h-12 w-12 object-contain">
                    <div>
                        <h3 class="font-bold text-lg" style="color: var(--muni-red-dark); font-family:'Merriweather',serif;">Welcome to Muni News Portal</h3>
                        <p class="text-sm text-gray-600">Transforming Lives — Manage your content from here. Use the <a href="{{ route('admin.dashboard') }}" class="font-semibold hover:text-[var(--muni-red)]" style="color: var(--muni-red);">Admin Panel</a> for full editorial access.</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="btn-muni text-xs px-4 py-2">Go to Admin</a>
                    <a href="{{ url('/') }}" class="text-xs px-4 py-2 rounded-sm border hover:bg-gray-50">View Site</a>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-sm border p-6">
                <p class="text-sm text-gray-700">You are logged in as <strong>{{ auth()->user()->full_name ?? auth()->user()->username }}</strong> ({{ auth()->user()->getRoleNames()->first() ?? 'user' }}).</p>
            </div>
        </div>
    </div>
</x-app-layout>
