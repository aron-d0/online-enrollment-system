<x-layout title="Create Subject">

    <div class="mb-8">

        <a href="{{ route('subjects.index') }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-slate-300 transition hover:bg-white/10">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Subjects
        </a>

        <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-sm text-blue-300">
            <i class="fa-solid fa-book-medical"></i>
            Admin Subject Creator
        </div>

        <h1 class="mt-4 text-5xl font-bold text-white">
            Create Subject
        </h1>

        <p class="mt-2 text-slate-400">
            Add a new subject to the enrollment system.
        </p>

    </div>

    <div class="rounded-[32px] border border-white/10 bg-white/5 p-8 shadow-[0_20px_60px_rgba(0,0,0,.35)] backdrop-blur-2xl">

        <form action="{{ route('subjects.store') }}" method="POST" class="space-y-6">

            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Section
                        </label>

                        <select name="section_id" required class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="" disabled {{ old('section_id') ? '' : 'selected' }}>
                                Select section
                            </option>

                            @foreach($sections as $section)

                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }} · {{ $section->semester }} · {{ $section->school_year }}
                                </option>

                            @endforeach

                        </select>

                        @error('section_id')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Subject Code
                        </label>

                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Ex. LN01_ELEC1" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('code')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Subject Title
                        </label>

                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex. Web Systems and Technologies 2" required
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('title')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Units
                        </label>

                        <input type="number" name="units" value="{{ old('units') }}" min="1" max="6" placeholder="Ex. 3" required
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('units')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Days
                        </label>

                        <input type="text" name="days" value="{{ old('days') }}" placeholder="Ex. MWF" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('days')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Room
                        </label>

                        <input type="text" name="room" value="{{ old('room') }}" placeholder="Ex. ITRM 3" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('room')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Time From
                        </label>

                        <input type="time" name="time_from" value="{{ old('time_from') }}" required
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('time_from')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300">
                            Time To
                        </label>

                        <input type="time" name="time_to" value="{{ old('time_to') }}" required
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('time_to')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">

                <a href="{{ route('subjects.index') }}"
                    class="rounded-2xl border border-white/10 bg-white/5 px-6 py-3 text-center font-semibold text-slate-300 transition hover:bg-white/10">
                    Cancel
                </a>

                <button type="submit"
                    class="rounded-2xl border border-blue-500/20 bg-blue-500/10 px-6 py-3 font-semibold text-blue-300 shadow-lg shadow-blue-950/20 backdrop-blur-xl transition hover:bg-blue-500/20 hover:text-blue-200">
                    Create Subject
                </button>

            </div>

        </form>

    </div>

</x-layout>
