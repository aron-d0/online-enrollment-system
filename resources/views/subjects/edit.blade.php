<x-layout title="Edit Subject">

    <div class="max-w-5xl mx-auto">

        <div class="mb-8">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm">

                <i class="fa-solid fa-book"></i>
                Subject Management

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Edit Subject
            </h1>

            <p class="mt-2 text-slate-400">
                Update subject information and schedule details
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-2xl
        border border-white/10
        rounded-[32px]
        p-8 shadow-[0_20px_60px_rgba(0,0,0,.25)]">

            <form action="{{ route('subjects.update', $subject->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Section
                        </label>

                        <select name="section_id" class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3 focus:border-blue-500 focus:ring-0" required>

                            @foreach($sections as $section)

                                <option value="{{ $section->id }}" {{ old('section_id', $subject->section_id) == $section->id ? 'selected' : '' }}>

                                    {{ $section->name }} · {{ $section->semester }} · {{ $section->school_year }}

                                </option>

                            @endforeach

                        </select>

                        @error('section_id')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Subject Code
                        </label>

                        <input type="text" name="code" value="{{ old('code', $subject->code) }}" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('code')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Subject Title
                        </label>

                        <input type="text" name="title" value="{{ old('title', $subject->title) }}" required
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('title')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Units
                        </label>

                        <input type="number" name="units" value="{{ old('units', $subject->units) }}" min="1" max="6" required
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('units')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Days
                        </label>

                        <input type="text" name="days" value="{{ old('days', $subject->days) }}" placeholder="MWF" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('days')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Room
                        </label>

                        <input type="text" name="room" value="{{ old('room', $subject->room) }}" placeholder="ITRM 3" required
                            oninput="this.value = this.value.toUpperCase()"
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('room')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Time From
                        </label>

                        <input type="time" name="time_from" value="{{ old('time_from', $subject->timeFromForInput()) }}" required
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('time_from')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Time To
                        </label>

                        <input type="time" name="time_to" value="{{ old('time_to', $subject->timeToForInput()) }}" required
                            class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                        @error('time_to')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="flex justify-between items-center mt-10">

                    <a href="{{ route('subjects.index') }}" class="px-5 py-3 rounded-2xl
                        bg-slate-800 border border-slate-700
                        text-slate-300 hover:bg-slate-700 transition">

                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Back

                    </a>

                    <button type="submit" class="px-6 py-3 rounded-2xl
                        bg-blue-500/20 border border-blue-500/20
                        text-blue-300 hover:bg-blue-500/30
                        transition-all duration-200 hover:scale-105">

                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Update Subject

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layout>
