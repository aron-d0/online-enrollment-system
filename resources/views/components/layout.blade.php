<!DOCTYPE html>
<html>

<head>
    <title>{{ isset($title) ? $title . ' | ' : '' }}{{ config('app.name', 'Online Enrollment System') }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}?v=1" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-white min-h-screen overflow-x-hidden">

    <!-- Background -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 -z-50"></div>

    <!-- Glow Effects -->
    <div class="fixed top-[-150px] left-[-150px] w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[120px] -z-40">
    </div>

    <div
        class="fixed bottom-[-150px] right-[-150px] w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[120px] -z-40">
    </div>

    <div
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-cyan-500/5 rounded-full blur-[150px] -z-40">
    </div>

    <div class="relative z-10">

        <!-- Floating Navbar -->
        <div class="sticky top-4 z-50 px-6 pt-4">

            <div
                class="max-w-7xl mx-auto bg-white/5 backdrop-blur-3xl border border-white/10 rounded-[32px] shadow-[0_20px_60px_rgba(0,0,0,.35)] overflow-hidden">

                <!-- Header -->
                <header class="px-8 py-5">

                    <div class="flex justify-between items-center">

                        <div class="flex items-center gap-5">

                            <img src="{{ asset('images/lingayen-university-logo.png') }}" alt="Logo"
                                class="w-14 h-14 object-contain">

                            <div>

                                <h1 class="font-bold text-2xl text-white tracking-tight">
                                    Lingayen University
                                </h1>

                                <p class="text-slate-400 text-sm">
                                    Online Enrollment System
                                </p>

                            </div>

                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="px-5 py-2.5 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-300 hover:bg-red-500/20 transition">

                                <i class="fa-solid fa-right-from-bracket mr-2"></i>
                                Logout

                            </button>

                        </form>

                    </div>

                </header>

                @if(auth()->check() && auth()->user()->role === 'admin')

                    <nav class="border-t border-white/10">

                        <div class="px-8 py-4 flex flex-wrap gap-3">

                            <a href="/admin"
                                class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-500/15 hover:border-blue-500/20 transition">

                                <i class="fa-solid fa-chart-pie mr-2"></i>
                                Dashboard

                            </a>

                            <a href="/admin/students"
                                class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-500/15 hover:border-blue-500/20 transition">

                                <i class="fa-solid fa-user-graduate mr-2"></i>
                                Students

                            </a>

                            <a href="/subjects"
                                class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-500/15 hover:border-blue-500/20 transition">

                                <i class="fa-solid fa-book mr-2"></i>
                                Subjects

                            </a>

                            <a href="/sections"
                                class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-500/15 hover:border-blue-500/20 transition">

                                <i class="fa-solid fa-layer-group mr-2"></i>
                                Sections

                            </a>

                            <a href="/admin/enrollments"
                                class="px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 text-slate-300 hover:text-white hover:bg-blue-500/15 hover:border-blue-500/20 transition">

                                <i class="fa-solid fa-file-signature mr-2"></i>
                                Enrollments

                            </a>

                        </div>

                    </nav>

                @endif

            </div>

        </div>

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-6 pt-10 pb-12">

            {{ $slot }}

        </main>

    </div>

    <div id="confirmationModal"
        class="fixed inset-0 z-[9998] hidden items-center justify-center px-6"
        aria-hidden="true">

        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" data-confirm-cancel></div>

        <div
            class="relative z-10 w-full max-w-md rounded-[32px] border border-white/10 bg-slate-900/95 p-7 text-center shadow-[0_25px_80px_rgba(0,0,0,.55)]">

            <div
                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-amber-400/20 bg-amber-400/10 text-amber-300">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>

            <h2 class="mt-5 text-2xl font-bold text-white" data-confirm-title>
                Confirm Action
            </h2>

            <p class="mt-3 text-slate-400" data-confirm-message>
                Are you sure you want to continue?
            </p>

            <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">

                <button type="button"
                    class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 font-semibold text-slate-300 transition hover:bg-white/10"
                    data-confirm-cancel>
                    Cancel
                </button>

                <button type="button"
                    class="rounded-2xl border border-red-500/20 bg-red-500/15 px-5 py-3 font-semibold text-red-300 transition hover:bg-red-500/25"
                    data-confirm-ok>
                    Confirm
                </button>

            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirmationModal');

            if (!modal) {
                return;
            }

            const titleTarget = modal.querySelector('[data-confirm-title]');
            const messageTarget = modal.querySelector('[data-confirm-message]');
            const okButton = modal.querySelector('[data-confirm-ok]');
            const cancelTargets = modal.querySelectorAll('[data-confirm-cancel]');
            let resolver = null;

            const closeModal = (result) => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('overflow-hidden');

                if (resolver) {
                    resolver(result);
                    resolver = null;
                }
            };

            window.confirmAction = ({
                title = 'Confirm Action',
                message = 'Are you sure you want to continue?',
                confirmText = 'Confirm',
            } = {}) => {
                titleTarget.textContent = title;
                messageTarget.textContent = message;
                okButton.textContent = confirmText;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('overflow-hidden');

                setTimeout(() => okButton.focus(), 50);

                return new Promise((resolve) => {
                    resolver = resolve;
                });
            };

            okButton.addEventListener('click', () => closeModal(true));
            cancelTargets.forEach((target) => {
                target.addEventListener('click', () => closeModal(false));
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal(false);
                }
            });

            document.querySelectorAll('form[data-confirm-message]').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    if (form.dataset.confirmed === 'true') {
                        form.dataset.confirmed = 'false';
                        return;
                    }

                    event.preventDefault();

                    const confirmed = await window.confirmAction({
                        title: form.dataset.confirmTitle || 'Confirm Action',
                        message: form.dataset.confirmMessage,
                        confirmText: form.dataset.confirmButton || 'Confirm',
                    });

                    if (confirmed) {
                        form.dataset.confirmed = 'true';
                        form.requestSubmit();
                    }
                });
            });
        });
    </script>

</body>

</html>
