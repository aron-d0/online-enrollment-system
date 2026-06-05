<x-layout title="Create Subject">

    <div class="max-w-5xl mx-auto">

        <div class="mb-8">

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm">

                <i class="fa-solid fa-book-medical"></i>
                Subject Management

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Create Subject
            </h1>

            <p class="mt-2 text-slate-400">
                Add a new subject to the enrollment system
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-2xl
        border border-white/10
        rounded-[32px]
        p-8 shadow-[0_20px_60px_rgba(0,0,0,.25)]">

            <form action="{{ route('subjects.store') }}" method="POST">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Section
                        </label>

                        <select name="section_id" required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">

                            @foreach($sections as $section)

                                <option value="{{ $section->id }}">
                                    {{ $section->name }}
                                </option>

                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Subject Code
                        </label>

                        <input type="text" name="code" placeholder="LN01_ELEC1" required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Subject Title
                        </label>

                        <input type="text" name="title" placeholder="Web Systems and Technologies 2" required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Units
                        </label>

                        <input type="number" name="units" min="1" placeholder="3" required class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Days
                        </label>

                        <input type="text" name="days" placeholder="MWF" class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Room
                        </label>

                        <input type="text" name="room" placeholder="ITRM 3" class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Time From
                        </label>

                        <input type="time" name="time_from" class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
                    </div>

                    <div>
                        <label class="block text-slate-300 mb-2">
                            Time To
                        </label>

                        <input type="time" name="time_to" class="w-full rounded-2xl bg-slate-900/60 border border-slate-700
                            text-white px-4 py-3">
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
                        Save Subject

                    </button>

                </div>

            </form>

        </div>

    </div>

</x-layout>