<x-guest-layout>

    <div class="text-center mb-6">

        <h2 class="text-2xl font-bold text-white">
            Forgot Password
        </h2>

        <p class="mt-2 text-slate-400">
            Enter your email address and we'll send you a password reset link.
        </p>

    </div>

    <x-auth-session-status class="mb-4 text-green-400" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <div>

            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />

            <x-text-input id="email" class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="email" name="email" :value="old('email')" required autofocus />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>

        <div class="mt-6 space-y-3">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg shadow-lg">

                Email Password Reset Link

            </button>

            <a href="{{ route('login') }}" class="block w-full text-center
                bg-slate-800 hover:bg-slate-700
                border border-slate-700
                text-slate-300
                font-semibold
                py-3
                rounded-lg
                transition">

                ← Back to Login

            </a>

        </div>

    </form>

</x-guest-layout>