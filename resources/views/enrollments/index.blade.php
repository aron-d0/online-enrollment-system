<x-layout title="Enrollment Reports">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8">

        <div>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-purple-500/10 border border-purple-500/20 text-purple-300 text-sm">

                <i class="fa-solid fa-chart-line"></i>
                Enrollment Analytics

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Enrollment Reports
            </h1>

            <p class="mt-2 text-slate-400">
                Review, approve, and manage student enrollments
            </p>

        </div>

        <div class="flex gap-3">

            <a href="{{ route('enrollments.export.json') }}" class="px-5 py-3 rounded-2xl
                bg-blue-500/20 border border-blue-500/20
                text-blue-300 hover:bg-blue-500/30 transition">

                <i class="fa-solid fa-file-code mr-2"></i>
                Export JSON

            </a>

            <a href="{{ route('enrollments.export.csv') }}" class="px-5 py-3 rounded-2xl
                bg-emerald-500/20 border border-emerald-500/20
                text-emerald-300 hover:bg-emerald-500/30 transition">

                <i class="fa-solid fa-file-csv mr-2"></i>
                Export CSV

            </a>

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

    <div class="overflow-hidden rounded-[32px]
    bg-white/5 backdrop-blur-2xl
    border border-white/10
    shadow-[0_20px_60px_rgba(0,0,0,.25)]">

        <table class="w-full">

            <thead>

                <tr class="border-b border-white/10 bg-white/5">

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Student Number
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Student Name
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Subject Code
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Subject
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Units
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold">
                        Status
                    </th>

                    <th class="px-6 py-5 text-left text-slate-300 font-semibold w-[340px]">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($enrollments as $enrollment)

                    <tr class="border-b border-white/5 hover:bg-white/5 transition">

                        <td class="px-6 py-4 text-slate-200">
                            {{ $enrollment->student->student_number }}
                        </td>

                        <td class="px-6 py-4 text-white font-medium">
                            {{ $enrollment->student->user->name }}
                        </td>

                        <td class="px-6 py-4 text-cyan-300 font-medium">
                            {{ $enrollment->subject->code }}
                        </td>

                        <td class="px-6 py-4 text-slate-200">
                            {{ $enrollment->subject->title }}
                        </td>

                        <td class="px-6 py-4 text-slate-200">
                            {{ $enrollment->subject->units }}
                        </td>

                        <td class="px-6 py-4">

                            @if($enrollment->status === 'Approved')

                                <span class="px-3 py-1 rounded-full
                                                                bg-emerald-500/20 text-emerald-300 text-sm">

                                    Approved

                                </span>

                            @elseif($enrollment->status === 'Rejected')

                                <span class="px-3 py-1 rounded-full
                                                                bg-red-500/20 text-red-300 text-sm">

                                    Rejected

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full
                                                                bg-yellow-500/20 text-yellow-300 text-sm">

                                    Pending

                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-2 whitespace-nowrap">

                                @if($enrollment->status === 'Pending')

                                            <form method="POST" action="{{ route('enrollments.approve', $enrollment->id) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="h-10 px-4 rounded-full
                                    bg-emerald-500/15 border border-emerald-500/20
                                    text-emerald-300 hover:bg-emerald-500/25
                                    transition-all duration-200 hover:scale-105">

                                                    <i class="fa-solid fa-check mr-2"></i>
                                                    Approve

                                                </button>

                                            </form>

                                            <form method="POST" action="{{ route('enrollments.reject', $enrollment->id) }}">

                                                @csrf
                                                @method('PATCH')

                                                <button type="submit" class="h-10 px-4 rounded-full
                                    bg-red-500/15 border border-red-500/20
                                    text-red-300 hover:bg-red-500/25
                                    transition-all duration-200 hover:scale-105">

                                                    <i class="fa-solid fa-xmark mr-2"></i>
                                                    Reject

                                                </button>

                                            </form>

                                @endif

                                <form method="POST" action="{{ route('enrollments.destroy', $enrollment->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" onclick="return confirm('Delete this enrollment?')" class="h-10 px-4 rounded-full
                    bg-slate-700/50 border border-slate-600
                    text-slate-300 hover:bg-slate-600
                    transition-all duration-200 hover:scale-105">

                                        <i class="fa-solid fa-trash mr-2"></i>
                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const scrollPosition = sessionStorage.getItem('enrollmentScroll');

            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('enrollmentScroll');
            }

            document.querySelectorAll('form').forEach(form => {

                form.addEventListener('submit', () => {

                    sessionStorage.setItem(
                        'enrollmentScroll',
                        window.scrollY
                    );

                });

            });

        });
    </script>

</x-layout>