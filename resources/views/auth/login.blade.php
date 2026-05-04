<x-guest-layout>
    <!-- App description box -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg">
        <h2 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">Service Desk</h2>
        <p class="text-xs text-blue-700 dark:text-blue-400 mb-3">
            Multi-tenant IT ticketing system. Log in as super admin to manage tenants, or use a tenant account to explore the service desk.
        </p>

        <div class="bg-white dark:bg-gray-800 rounded p-2 text-xs text-gray-700 dark:text-gray-300 font-mono mb-2">
            <span class="font-semibold text-gray-900 dark:text-gray-100">Super Admin</span>
            <span class="text-gray-400 font-sans"> — manages the platform &amp; tenants</span><br>
            Email: <span class="text-indigo-600 dark:text-indigo-400">superadmin@system.com</span><br>
            Password: <span class="text-indigo-600 dark:text-indigo-400">superadmin123</span>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded p-2 text-xs text-gray-700 dark:text-gray-300 font-mono">
            <span class="font-semibold text-gray-900 dark:text-gray-100">Demo Tenant Admin</span>
            <span class="text-gray-400 font-sans"> — manages Demo Company</span><br>
            Email: <span class="text-indigo-600 dark:text-indigo-400">admin@democompany.com</span><br>
            Password: <span class="text-indigo-600 dark:text-indigo-400">password</span>
        </div>

        <p class="text-xs text-blue-500 dark:text-blue-500 mt-2">⚠ Demo data is reset every 24 hours.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
