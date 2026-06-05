<!DOCTYPE html>
<html>

<head>
    <title>{{ $title ?? 'Online Enrollment System' }}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

                                <i class="fa-solid fa-chart-line mr-2"></i>
                                Reports

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

</body>

</html>