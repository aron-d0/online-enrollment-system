<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-950">

        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950"></div>

        <!-- Glow Effects -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 w-full max-w-md px-6">

            <!-- Branding -->
            <div class="text-center mb-8">

                <img src="{{ asset('images/lingayen-university-logo.png') }}" alt="Lingayen University Logo"
                    class="w-[125px] h-[125px] mx-auto object-contain" />

                <h1 class="text-4xl font-bold text-white">
                    Lingayen University
                </h1>

                <p class="mt-2 text-slate-400">
                    Online Enrollment System
                </p>

            </div>

            <!-- Login Card -->
            <div class="bg-slate-900/70 backdrop-blur-xl border border-slate-700 shadow-2xl rounded-2xl px-8 py-8">

                {{ $slot }}

            </div>

        </div>

    </div>
</body>

</html>