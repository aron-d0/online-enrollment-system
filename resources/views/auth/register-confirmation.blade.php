<x-guest-layout>

    <div class="text-center">

        <div class="relative mx-auto mb-7 flex h-24 w-24 items-center justify-center">

            <div class="absolute inset-0 rounded-full bg-blue-500/20 blur-2xl"></div>

            <div class="absolute inset-2 rounded-full border border-blue-400/30 bg-slate-800/80 shadow-2xl"></div>

            <div
                class="relative flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-950/60 ring-4 ring-blue-400/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

        </div>

        <h2 class="text-2xl font-bold text-white">
            Registration Complete
        </h2>

        <p class="mt-3 text-slate-400">
            Your student account has been created successfully.
        </p>

        @if (session('registered_email'))
            <div class="mt-5 rounded-xl border border-blue-400/20 bg-slate-800/70 px-4 py-4 text-left shadow-inner">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Account Email
                </p>

                <p class="mt-1 break-all text-sm font-semibold text-blue-300">
                    {{ session('registered_email') }}
                </p>

                <p class="mt-2 text-xs text-slate-400">
                    Use this email and your password to sign in.
                </p>
            </div>
        @endif

        <a href="{{ route('login') }}"
            class="mt-6 block w-full rounded-lg bg-blue-600 py-3 font-semibold text-white shadow-lg shadow-blue-950/40 transition hover:bg-blue-700">
            Proceed to Login
        </a>

    </div>

</x-guest-layout>
