<x-layout title="Section Management">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8">

        <div>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-sm">

                <i class="fa-solid fa-layer-group"></i>
                Academic Sections

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Sections
            </h1>

            <p class="mt-2 text-slate-400">
                Manage class sections and academic groupings
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10
        rounded-3xl px-6 py-4">

            <p class="text-slate-400 text-sm">
                Total Sections
            </p>

            <h2 class="text-3xl font-bold text-white">
                {{ $sections->count() }}
            </h2>

        </div>

    </div>

    <!-- Actions -->
    <div class="flex justify-end mb-8">

        <a href="{{ route('sections.create') }}" class="px-5 py-3 rounded-2xl
            bg-cyan-500/20 border border-cyan-500/20
            text-cyan-300 hover:bg-cyan-500/30 transition">

            <i class="fa-solid fa-plus mr-2"></i>
            Create Section

        </a>

    </div>

    @if($sections->count())

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            @foreach($sections as $section)

                <div
                    class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[32px] p-6 shadow-[0_20px_60px_rgba(0,0,0,.25)] hover:bg-white/10 hover:-translate-y-1 transition duration-300">

                    <div class="flex justify-between items-start mb-5">

                        <div>

                            <h2 class="text-2xl font-bold text-white">
                                {{ $section->name }}
                            </h2>

                            <p class="text-cyan-300 mt-1">
                                Academic Section
                            </p>

                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/20 flex items-center justify-center">

                            <i class="fa-solid fa-layer-group text-cyan-400"></i>

                        </div>

                    </div>

                    <div class="space-y-4">

                        <div class="flex justify-between">

                            <span class="text-slate-400">
                                Semester
                            </span>

                            <span class="text-white font-medium">
                                {{ $section->semester }}
                            </span>

                        </div>

                        <div class="flex justify-between">

                            <span class="text-slate-400">
                                School Year
                            </span>

                            <span class="text-white font-medium">
                                {{ $section->school_year }}
                            </span>

                        </div>

                    </div>

                    <div class="flex gap-3 mt-6">

                        <a href="{{ route('sections.edit', $section->id) }}" class="flex-1 text-center px-4 py-3 rounded-2xl
                                    bg-blue-500/20 border border-blue-500/20
                                    text-blue-300 hover:bg-blue-500/30 transition">

                            <i class="fa-solid fa-pen mr-2"></i>
                            Edit

                        </a>

                        <form action="{{ route('sections.destroy', $section->id) }}" method="POST" class="flex-1">

                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Delete this section?')" class="w-full px-4 py-3 rounded-2xl
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

            <i class="fa-solid fa-layer-group text-5xl text-slate-500"></i>

            <p class="mt-4 text-slate-400">
                No sections found.
            </p>

        </div>

    @endif

</x-layout>