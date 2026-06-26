<x-layout title="Edit Section">

    <div class="mb-8">

        <a href="{{ route('sections.index') }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-slate-300 transition hover:bg-white/10">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Sections
        </a>

        <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-cyan-500/20 bg-cyan-500/10 px-4 py-2 text-sm text-cyan-300">
            <i class="fa-solid fa-layer-group"></i>
            Admin Section Editor
        </div>

        <h1 class="mt-4 text-5xl font-bold text-white">
            Edit Section
        </h1>

        <p class="mt-2 text-slate-400">
            Update section information and academic details.
        </p>

    </div>

    <div class="rounded-[32px] border border-white/10 bg-white/5 p-8 shadow-[0_20px_60px_rgba(0,0,0,.35)] backdrop-blur-2xl">

        <form action="{{ route('sections.update', $section->id) }}" method="POST" class="space-y-6">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300">
                        Section Name
                    </label>

                    <input id="name" type="text" name="name" value="{{ old('name', $section->name) }}"
                        placeholder="Ex. III-BSIT-A" required
                        oninput="this.value = this.value.toUpperCase()"
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-cyan-500 focus:ring-cyan-500">

                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="semester" class="block text-sm font-medium text-slate-300">
                        Semester
                    </label>

                    <select id="semester" name="semester" required
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-cyan-500 focus:ring-cyan-500">

                        @foreach(['1st Semester', '2nd Semester', 'Summer'] as $semester)

                            <option value="{{ $semester }}" {{ old('semester', $section->semester) === $semester ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>

                        @endforeach

                    </select>

                    @error('semester')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="school_year" class="block text-sm font-medium text-slate-300">
                        School Year
                    </label>

                    <input id="school_year" type="text" name="school_year" value="{{ old('school_year', $section->school_year) }}"
                        placeholder="Ex. 2026-2027" pattern="\d{4}-\d{4}" required
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-cyan-500 focus:ring-cyan-500">

                    <p class="mt-2 text-sm text-slate-500">
                        Use format YYYY-YYYY.
                    </p>

                    @error('school_year')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">

                <a href="{{ route('sections.index') }}"
                    class="rounded-2xl border border-white/10 bg-white/5 px-6 py-3 text-center font-semibold text-slate-300 transition hover:bg-white/10">
                    Cancel
                </a>

                <button type="submit"
                    class="rounded-2xl border border-blue-500/20 bg-blue-500/10 px-6 py-3 font-semibold text-blue-300 shadow-lg shadow-blue-950/20 backdrop-blur-xl transition hover:bg-blue-500/20 hover:text-blue-200">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</x-layout>
