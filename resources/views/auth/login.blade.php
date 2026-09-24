<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold" style="font-family: 'Merriweather', Georgia, serif; color: var(--muni-red);">Admin Login</h2>
        <p class="text-sm text-gray-600 mt-1">Sign in to Muni News Portal</p>
        <div class="w-12 h-1 mx-auto mt-3" style="background: var(--muni-gold);"></div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <x-text-input id="email" class="block w-full pl-10" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@muni.ac.ug" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative mt-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa-solid fa-lock"></i>
                </span>
                <x-text-input id="password" class="block w-full pl-10"
                                type="password"
                                name="password"
                                required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[var(--muni-red)] shadow-sm focus:ring-[var(--muni-blue)]" name="remember" style="color: var(--muni-red);">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                <i class="fa-solid fa-right-to-bracket me-2"></i> {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-between mt-6 text-sm">
            @if (Route::has('password.request'))
                <a class="underline text-gray-600 hover:text-[var(--muni-red)] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--muni-blue)]" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <a href="{{ url('/') }}" class="underline text-gray-600 hover:text-[var(--muni-red)]">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to site
            </a>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">
                Demo: <span class="font-semibold" style="color: var(--muni-red);">admin@muni.ac.ug</span> / <span class="font-semibold">password</span>
            </p>
        </div>
    </form>
</x-guest-layout>
