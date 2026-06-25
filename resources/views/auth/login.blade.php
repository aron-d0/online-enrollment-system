<x-guest-layout>

    <div class="text-center mb-6">

        <h2 class="text-2xl font-bold text-white">
            Welcome Back
        </h2>

        <p class="mt-2 text-slate-400">
            Sign in to access the enrollment system
        </p>

    </div>

    <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">

        @csrf

        <div>

            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />

            <x-text-input id="email" class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>

        <div class="mt-5">

            <x-input-label for="password" :value="__('Password')" class="text-slate-300" />

            <x-text-input id="password" class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>

        <div class="flex items-center justify-between mt-4">

            @if (Route::has('password.request'))

                <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300 transition">
                    Forgot Password?
                </a>

            @endif

        </div>

        <div class="mt-6">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg shadow-lg">

                Log In

            </button>

        </div>

    </form>

    @if (Route::has('register'))

        <div class="mt-6 pt-6 border-t border-slate-700 text-center">

            <p class="text-sm text-slate-400">
                New to the enrollment system?
            </p>

            <a href="{{ route('register') }}"
                class="mt-3 block w-full border border-blue-500/70 text-blue-300 hover:bg-blue-500/10 hover:text-blue-200 transition font-semibold py-3 rounded-lg">
                Create an Account
            </a>

        </div>

    @endif

</x-guest-layout>
