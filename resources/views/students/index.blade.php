<x-layout title="Student Management">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-8">

        <div>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-blue-500/10 border border-blue-500/20 text-blue-300 text-sm">

                <i class="fa-solid fa-user-graduate"></i>
                Student Records

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Students
            </h1>

            <p class="mt-2 text-slate-400">
                Manage enrolled student information and academic records
            </p>

        </div>

        <div class="mt-4 flex flex-col gap-3 md:mt-0 md:items-end">

            <div class="bg-white/5 backdrop-blur-xl border border-white/10
            rounded-3xl px-6 py-4">

                <p class="text-slate-400 text-sm">
                    Total Students
                </p>

                <h2 class="text-3xl font-bold text-white">
                    {{ $students->count() }}
                </h2>

            </div>

            <a href="{{ route('students.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-blue-500/20 bg-blue-500/10 px-5 py-3 font-semibold text-blue-300 transition hover:bg-blue-500/20">
                <i class="fa-solid fa-user-plus"></i>
                Add Student
            </a>

        </div>

    </div>

    @if (session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-emerald-300">
            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Student Table -->
    <div class="overflow-hidden rounded-[32px]
    bg-white/5 backdrop-blur-2xl
    border border-white/10
    shadow-[0_20px_60px_rgba(0,0,0,.35)]">

        <table class="w-full">

            <thead>

                <tr class="border-b border-white/10">

                    <th class="px-6 py-5 text-left text-slate-400 font-medium">
                        Student Number
                    </th>

                    <th class="px-6 py-5 text-left text-slate-400 font-medium">
                        Name
                    </th>

                    <th class="px-6 py-5 text-left text-slate-400 font-medium">
                        Course
                    </th>

                    <th class="px-6 py-5 text-left text-slate-400 font-medium">
                        Year
                    </th>

                    <th class="px-6 py-5 text-left text-slate-400 font-medium">
                        Email
                    </th>

                    <th class="px-6 py-5 text-right text-slate-400 font-medium">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($students as $student)

                    <tr class="border-b border-white/5 hover:bg-white/5 transition">

                        <td class="px-6 py-5 text-slate-300">
                            {{ $student->student_number }}
                        </td>

                        <td class="px-6 py-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-full
                                    bg-blue-500/20 flex items-center justify-center">

                                    <i class="fa-solid fa-user text-blue-400"></i>

                                </div>

                                <span class="text-white font-medium">
                                    {{ $student->user->name }}
                                </span>

                            </div>

                        </td>

                        <td class="px-6 py-5 text-slate-300">
                            {{ $student->course }}
                        </td>

                        <td class="px-6 py-5">

                            <span class="px-3 py-1 rounded-full
                                bg-cyan-500/10 border border-cyan-500/20
                                text-cyan-300 text-sm">

                                {{ $student->year_level }}

                            </span>

                        </td>

                        <td class="px-6 py-5 text-slate-300">
                            {{ $student->user->email }}
                        </td>

                        <td class="px-6 py-5">

                            <div class="flex items-center justify-end gap-3">

                                <a href="{{ route('students.edit', $student) }}"
                                    class="inline-flex items-center gap-2 rounded-2xl border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-sm font-semibold text-blue-300 transition hover:bg-blue-500/20">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('students.destroy', $student) }}"
                                    data-confirm-title="Delete Student"
                                    data-confirm-message="Delete this student account? This will remove their login and student record."
                                    data-confirm-button="Delete">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300 transition hover:bg-red-500/20">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @endforeach

                @if ($students->isEmpty())

                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                            No student records found.
                        </td>
                    </tr>

                @endif

            </tbody>

        </table>

    </div>

</x-layout>
