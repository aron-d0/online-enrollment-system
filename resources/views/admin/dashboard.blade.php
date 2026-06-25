<x-layout title="Admin Dashboard">

    <div class="mb-10">

        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
        bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm">

            <i class="fa-solid fa-shield-halved"></i>
            Administrator Portal

        </div>

        <h1 class="mt-4 text-5xl font-bold text-white">
            Dashboard
        </h1>

        <p class="mt-3 text-slate-400 text-lg">
            Welcome back, {{ auth()->user()->name }}
        </p>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Students -->
        <div
            class="group bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-2xl hover:bg-white/10 transition">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-slate-400 text-sm">
                        Total Students
                    </p>

                    <h2 class="text-5xl font-bold text-white mt-2">
                        {{ $studentCount }}
                    </h2>

                    <p class="text-emerald-400 text-sm mt-3">
                        Active records
                    </p>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-user-graduate text-blue-400 text-xl"></i>
                </div>

            </div>

        </div>

        <!-- Subjects -->
        <div
            class="group bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-2xl hover:bg-white/10 transition">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-slate-400 text-sm">
                        Subjects
                    </p>

                    <h2 class="text-5xl font-bold text-white mt-2">
                        {{ $subjectCount }}
                    </h2>

                    <p class="text-blue-400 text-sm mt-3">
                        Available courses
                    </p>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-indigo-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-book text-indigo-400 text-xl"></i>
                </div>

            </div>

        </div>

        <!-- Sections -->
        <div
            class="group bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-2xl hover:bg-white/10 transition">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-slate-400 text-sm">
                        Sections
                    </p>

                    <h2 class="text-5xl font-bold text-white mt-2">
                        {{ $sectionCount }}
                    </h2>

                    <p class="text-cyan-400 text-sm mt-3">
                        Academic sections
                    </p>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-cyan-400 text-xl"></i>
                </div>

            </div>

        </div>

        <!-- Enrollments -->
        <div
            class="group bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-6 shadow-2xl hover:bg-white/10 transition">

            <div class="flex justify-between items-start">

                <div>

                    <p class="text-slate-400 text-sm">
                        Enrollments
                    </p>

                    <h2 class="text-5xl font-bold text-white mt-2">
                        {{ $enrollmentCount }}
                    </h2>

                    <p class="text-purple-400 text-sm mt-3">
                        Submitted requests
                    </p>

                </div>

                <div class="w-14 h-14 rounded-2xl bg-purple-500/20 flex items-center justify-center">
                    <i class="fa-solid fa-file-signature text-purple-400 text-xl"></i>
                </div>

            </div>

        </div>

    </div>

    <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-8">

        <h2 class="text-2xl font-semibold text-white mb-6">
            Quick Actions
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Students -->
            <a href="/admin/students"
                class="group bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-blue-500/10 transition">

                <i class="fa-solid fa-user-graduate text-3xl text-blue-400"></i>

                <h3 class="mt-4 text-lg font-semibold text-white">
                    Students
                </h3>

                <p class="text-slate-400 text-sm mt-1">
                    Manage student records
                </p>

            </a>

            <!-- Subjects -->
            <a href="/subjects"
                class="group bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-indigo-500/10 transition">

                <i class="fa-solid fa-book text-3xl text-indigo-400"></i>

                <h3 class="mt-4 text-lg font-semibold text-white">
                    Subjects
                </h3>

                <p class="text-slate-400 text-sm mt-1">
                    Manage course offerings
                </p>

            </a>

            <!-- Sections -->
            <a href="/sections"
                class="group bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-cyan-500/10 transition">

                <i class="fa-solid fa-layer-group text-3xl text-cyan-400"></i>

                <h3 class="mt-4 text-lg font-semibold text-white">
                    Sections
                </h3>

                <p class="text-slate-400 text-sm mt-1">
                    Manage class sections
                </p>

            </a>

            <!-- Enrollments -->
            <a href="/admin/enrollments"
                class="group bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-purple-500/10 transition">

                <i class="fa-solid fa-file-signature text-3xl text-purple-400"></i>

                <h3 class="mt-4 text-lg font-semibold text-white">
                    Enrollments
                </h3>

                <p class="text-slate-400 text-sm mt-1">
                    Review enrollments
                </p>

            </a>

        </div>

    </div>

</x-layout>
