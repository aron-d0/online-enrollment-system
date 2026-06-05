<x-layout title="Subject Management">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8">

        <div>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-sm">

                <i class="fa-solid fa-book"></i>
                Academic Subjects

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Subjects
            </h1>

            <p class="mt-2 text-slate-400">
                Manage course offerings and schedules
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10
        rounded-3xl px-6 py-4">

            <p class="text-slate-400 text-sm">
                Total Subjects
            </p>

            <h2 class="text-3xl font-bold text-white">
                {{ $subjects->count() }}
            </h2>

        </div>

    </div>

    @if(session('success'))

        <div class="mb-6 p-4 rounded-2xl
                    bg-emerald-500/10 border border-emerald-500/20
                    text-emerald-300">

            <i class="fa-solid fa-circle-check mr-2"></i>

            {{ session('success') }}

        </div>

    @endif

    <!-- Actions -->
    <div class="flex justify-between items-center mb-8">

        <form method="POST" action="{{ route('subjects.import') }}" enctype="multipart/form-data"
            class="flex items-center gap-3">

            @csrf

            <form method="POST" action="{{ route('subjects.import') }}" enctype="multipart/form-data"
                class="flex items-center gap-3">

                @csrf

                <label class="
        flex items-center gap-3
        px-4 py-3
        rounded-2xl
        bg-white/5
        border border-white/10
        backdrop-blur-xl
        text-slate-300
        cursor-pointer
        hover:bg-white/10
        transition">

                    <i class="fa-solid fa-file-csv text-blue-400"></i>

                    <span id="csv-file-name">
                        Choose CSV File
                    </span>

                    <input type="file" name="csv_file" accept=".csv" required class="hidden" onchange="
                document.getElementById('csv-file-name').innerText =
                this.files[0]?.name || 'Choose CSV File';
            ">

                </label>

                <button type="submit" class="px-5 py-3 rounded-2xl
        bg-blue-500/20 border border-blue-500/20
        text-blue-300 hover:bg-blue-500/30 transition">

                    <i class="fa-solid fa-file-import mr-2"></i>
                    Import CSV

                </button>

            </form>

            <!-- <button type="submit" class="px-5 py-3 rounded-2xl
            bg-blue-500/20 border border-blue-500/20
            text-blue-300 hover:bg-blue-500/30 transition">

                <i class="fa-solid fa-file-import mr-2"></i>
                Import CSV

            </button> -->

        </form>

        <a href="{{ route('subjects.create') }}" class="px-5 py-3 rounded-2xl
        bg-indigo-500/20 border border-indigo-500/20
        text-indigo-300 hover:bg-indigo-500/30 transition">

            <i class="fa-solid fa-plus mr-2"></i>
            Create Subject

        </a>

    </div>

    @if($subjects->count())

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            @foreach($subjects as $subject)

                <div class="
                                        bg-white/5
                                        backdrop-blur-2xl
                                        border border-white/10
                                        rounded-[32px]
                                        p-6
                                        shadow-[0_20px_60px_rgba(0,0,0,.25)]
                                        hover:bg-white/10
                                        hover:-translate-y-1
                                        transition duration-300">

                    <div class="flex justify-between items-start mb-4">

                        <div>

                            <div class="text-indigo-400 text-sm font-medium">
                                {{ $subject->code }}
                            </div>

                            <h2 class="text-xl font-bold text-white mt-1">
                                {{ $subject->title }}
                            </h2>

                        </div>

                        <div class="w-12 h-12 rounded-2xl
                                                bg-indigo-500/20 flex items-center justify-center">

                            <i class="fa-solid fa-book text-indigo-400"></i>

                        </div>

                    </div>

                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between">

                            <span class="text-slate-400">
                                Units
                            </span>

                            <span class="text-white font-medium">
                                {{ $subject->units }}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-slate-400">
                                Section
                            </span>

                            <span class="text-cyan-300">
                                {{ $subject->section->name }}
                            </span>

                        </div>

                        <div>

                            <p class="text-slate-400 mb-1">
                                Schedule
                            </p>

                            <p class="text-white">
                                {{ $subject->days }}
                            </p>

                            <p class="text-slate-300 text-sm">
                                {{ $subject->time_from }}
                                -
                                {{ $subject->time_to }}
                            </p>

                        </div>

                    </div>

                    <div class="flex gap-3 mt-6">

                        <a href="{{ route('subjects.edit', $subject->id) }}" class="flex-1 text-center px-4 py-3 rounded-2xl
                                                    bg-blue-500/20 border border-blue-500/20
                                                    text-blue-300 hover:bg-blue-500/30 transition">

                            <i class="fa-solid fa-pen mr-2"></i>
                            Edit

                        </a>

                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="flex-1">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="w-full px-4 py-3 rounded-2xl
                                                        bg-red-500/20 border border-red-500/20
                                                        text-red-300 hover:bg-red-500/30 transition">

                                <i class="fa-solid fa-trash mr-2"></i>
                                Delete

                            </button>

                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="bg-white/5 border border-white/10 rounded-[32px] p-10 text-center">

            <i class="fa-solid fa-book-open text-5xl text-slate-500"></i>

            <p class="mt-4 text-slate-400">
                No subjects found.
            </p>

        </div>

    @endif

</x-layout>