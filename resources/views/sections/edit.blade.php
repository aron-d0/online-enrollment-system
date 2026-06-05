<x-layout title="Edit Section">

    <div class="max-w-4xl mx-auto">

        <div class="mb-8">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-sm">

                <i class="fa-solid fa-layer-group"></i>
                Section Management

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Edit Section
            </h1>

            <p class="mt-2 text-slate-400">
                Update section information and academic details
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-2xl
        border border-white/10
        rounded-[32px]
        p-8 shadow-[0_20px_60px_rgba(0,0,0,.25)]">

            <form action="{{ route('sections.update', $section->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="space-y-6">

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Section Name
                        </label>

                        <input type="text" name="name" value="{{ $section->name }}" placeholder="III-BSIT-A" required
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Semester
                        </label>

                        <input type="text" name="semester" value="{{ $section->semester }}" placeholder="1st Semester"
                            required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            School Year
                        </label>

                        <input type="text" name="school_year" value="{{ $section->school_year }}"
                            placeholder="2025-2026" required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                </div>

                <div class="flex justify-between items-center mt-10">

                    <a href="{{ route('sections.index') }}" class="px-5 py-3 rounded-2xl
                        bg-slate-800 border border-slate-700
                        text-slate-300 hover:bg-slate-700 transition">

                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Back

                    </a>

                    <button type="submit" class="px-6 py-3 rounded-2xl
                        bg-cyan-500/20 border border-cyan-500/20
                        text-cyan-300 hover:bg-cyan-500/30
                        transition-all duration-200 hover:scale-105">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Update Section

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layout>