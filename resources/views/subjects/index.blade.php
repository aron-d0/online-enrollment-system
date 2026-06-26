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
                Manage course offerings, schedules, and section subject lists
            </p>

        </div>

        <div class="bg-white/5 backdrop-blur-xl border border-white/10
        rounded-3xl px-6 py-4">

            <p class="text-slate-400 text-sm">
                Total Subjects
            </p>

            <h2 class="text-3xl font-bold text-white">
                {{ $subjectCount }}
            </h2>

        </div>

    </div>

    @if(session('success'))

        <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-4 text-emerald-300">

            <i class="fa-solid fa-circle-check mr-2"></i>
            {{ session('success') }}

        </div>

    @endif

    @if(session('error'))

        <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 px-5 py-4 text-red-300">

            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            {{ session('error') }}

        </div>

    @endif

    @error('csv_file')

        <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 px-5 py-4 text-red-300">

            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            {{ $message }}

        </div>

    @enderror

    @error('subject_ids')

        <div class="mb-6 rounded-2xl border border-red-500/20 bg-red-500/10 px-5 py-4 text-red-300">

            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
            Select at least one subject first.

        </div>

    @enderror

    <!-- Actions -->
    <div class="flex flex-col justify-between gap-4 mb-8 lg:flex-row lg:items-center">

        <div>

            <form method="POST" action="{{ route('subjects.import') }}" enctype="multipart/form-data"
                class="flex flex-wrap items-center gap-3">

                @csrf

                <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-slate-300 backdrop-blur-xl transition hover:bg-white/10">

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

            <p class="mt-2 text-xs text-slate-500">
                CSV columns: section_name, semester, school_year, code, title, units, days, time_from, time_to, room
            </p>

        </div>

        <a href="{{ route('subjects.create') }}" class="px-5 py-3 rounded-2xl
        bg-indigo-500/20 border border-indigo-500/20
        text-indigo-300 hover:bg-indigo-500/30 transition">

            <i class="fa-solid fa-plus mr-2"></i>
            Create Subject

        </a>

    </div>

    @if($sections->count())

        <div class="space-y-6">

            @foreach($sections as $section)

                @php
                    $sectionSubjects = $section->subjects;
                    $bulkFormId = 'bulk-delete-subjects-' . $section->id;
                @endphp

                <section class="overflow-hidden rounded-[32px] border border-white/10 bg-white/5 backdrop-blur-2xl shadow-[0_20px_60px_rgba(0,0,0,.25)]"
                    data-section-subject-group>

                    <div class="flex flex-col gap-4 border-b border-white/10 bg-white/5 px-6 py-5 md:flex-row md:items-center md:justify-between">

                        <div class="flex items-center gap-4">

                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-500/15 text-indigo-300">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>

                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ $section->name }}
                                </h2>

                                <p class="text-sm text-slate-400">
                                    {{ $section->semester }} · {{ $section->school_year }}
                                </p>
                            </div>

                        </div>

                        <div class="flex flex-wrap items-center gap-2 text-sm">

                            <span class="rounded-full bg-white/5 px-4 py-2 text-slate-300">
                                {{ $sectionSubjects->count() }} Subjects
                            </span>

                            <form id="{{ $bulkFormId }}"
                                action="{{ route('subjects.bulk-destroy') }}"
                                method="POST"
                                data-confirm-title="Delete Selected Subjects"
                                data-confirm-message="Delete the selected subjects in {{ $section->name }}?"
                                data-confirm-button="Delete">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="bulk-subject-delete rounded-full border border-red-500/20 bg-red-500/10 px-4 py-2 font-semibold text-red-300 transition hover:bg-red-500/20 disabled:cursor-not-allowed disabled:opacity-40"
                                    disabled>
                                    <i class="fa-solid fa-trash mr-2"></i>
                                    Delete Selected
                                </button>

                            </form>

                            <button type="button"
                                data-subject-toggle
                                aria-expanded="false"
                                title="Expand subjects"
                                aria-label="Expand subjects for {{ $section->name }}"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10">
                                <i class="fa-solid fa-chevron-up rotate-180 transition-transform duration-200" data-toggle-icon></i>
                            </button>

                        </div>

                    </div>

                    <div data-subject-panel class="hidden">

                        <div class="border-b border-white/10 px-6 py-4">
                            <p class="text-sm text-slate-400">
                                Select subjects with no enrollments, then delete them in bulk if needed.
                            </p>
                        </div>

                        <div class="overflow-hidden">

                            <table class="w-full table-fixed text-sm">

                                <colgroup>
                                    <col class="w-[4%]">
                                    <col class="w-[11%]">
                                    <col class="w-[25%]">
                                    <col class="w-[7%]">
                                    <col class="w-[9%]">
                                    <col class="w-[9%]">
                                    <col class="w-[10%]">
                                    <col class="w-[10%]">
                                    <col class="w-[7%]">
                                    <col class="w-[8%]">
                                </colgroup>

                                <thead>

                                    <tr class="border-b border-white/10">

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            <input type="checkbox"
                                                class="section-subject-select-all rounded border-slate-600 bg-slate-900 text-indigo-500 focus:ring-indigo-500"
                                                aria-label="Select all available subjects in {{ $section->name }}">
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Code
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Subject
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Units
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            From
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            To
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Days
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Room
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Used
                                        </th>

                                        <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                            Actions
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse($sectionSubjects as $subject)

                                        <tr class="border-b border-white/5 hover:bg-white/5 transition">

                                            <td class="px-3 py-3">
                                                <input type="checkbox"
                                                    name="subject_ids[]"
                                                    value="{{ $subject->id }}"
                                                    form="{{ $bulkFormId }}"
                                                    class="subject-checkbox rounded border-slate-600 bg-slate-900 text-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:opacity-40"
                                                    {{ $subject->enrollments_count > 0 ? 'disabled' : '' }}
                                                    aria-label="Select {{ $subject->code }}">
                                            </td>

                                            <td class="px-3 py-3 text-indigo-300 font-medium break-words">
                                                {{ $subject->code }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-200 leading-snug">
                                                {{ $subject->title }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-300">
                                                {{ $subject->units }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-300">
                                                {{ $subject->timeFromForDisplay() }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-300">
                                                {{ $subject->timeToForDisplay() }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-300 break-words">
                                                {{ $subject->days ?? '—' }}
                                            </td>

                                            <td class="px-3 py-3 text-slate-300 break-words">
                                                {{ $subject->room ?? '—' }}
                                            </td>

                                            <td class="px-3 py-3">
                                                @if($subject->enrollments_count > 0)
                                                    <span class="rounded-full bg-yellow-500/10 px-2.5 py-1 text-xs text-yellow-300">
                                                        {{ $subject->enrollments_count }}
                                                    </span>
                                                @else
                                                    <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-xs text-emerald-300">
                                                        0
                                                    </span>
                                                @endif
                                            </td>

                                            <td class="px-3 py-3">
                                                <div class="flex flex-wrap items-center gap-1.5">

                                                    <a href="{{ route('subjects.edit', $subject->id) }}"
                                                        title="Edit"
                                                        aria-label="Edit {{ $subject->code }}"
                                                        class="flex h-9 w-9 items-center justify-center rounded-full border border-blue-500/20 bg-blue-500/15 text-blue-300 transition hover:bg-blue-500/25">
                                                        <i class="fa-solid fa-pen"></i>
                                                        <span class="sr-only">Edit</span>
                                                    </a>

                                                    @if($subject->enrollments_count > 0)

                                                        <button type="button" disabled
                                                            title="Delete this subject's enrollments first."
                                                            aria-label="{{ $subject->code }} is in use"
                                                            class="flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-full border border-slate-600 bg-slate-700/30 text-slate-500">
                                                            <i class="fa-solid fa-lock"></i>
                                                            <span class="sr-only">In Use</span>
                                                        </button>

                                                    @else

                                                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST"
                                                            data-confirm-title="Delete Subject"
                                                            data-confirm-message="Delete {{ $subject->code }}?"
                                                            data-confirm-button="Delete">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                title="Delete"
                                                                aria-label="Delete {{ $subject->code }}"
                                                                class="flex h-9 w-9 items-center justify-center rounded-full border border-red-500/20 bg-red-500/15 text-red-300 transition hover:bg-red-500/25">
                                                                <i class="fa-solid fa-trash"></i>
                                                                <span class="sr-only">Delete</span>
                                                            </button>

                                                        </form>

                                                    @endif

                                                </div>
                                            </td>

                                        </tr>

                                    @empty

                                        <tr>
                                            <td colspan="10" class="px-6 py-10 text-center text-slate-400">
                                                No subjects in this section yet.
                                            </td>
                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </section>

            @endforeach

        </div>

    @else

        <div class="bg-white/5 border border-white/10 rounded-[32px] p-10 text-center">

            <i class="fa-solid fa-book-open text-5xl text-slate-500"></i>

            <p class="mt-4 text-slate-400">
                No sections found. Create a section first, then add subjects to it.
            </p>

        </div>

    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-section-subject-group]').forEach((group) => {
                const toggle = group.querySelector('[data-subject-toggle]');
                const panel = group.querySelector('[data-subject-panel]');
                const selectAll = group.querySelector('.section-subject-select-all');
                const checkboxes = Array.from(group.querySelectorAll('.subject-checkbox:not(:disabled)'));
                const bulkDelete = group.querySelector('.bulk-subject-delete');

                const updateBulkButton = () => {
                    const hasSelected = checkboxes.some((checkbox) => checkbox.checked);

                    if (bulkDelete) {
                        bulkDelete.disabled = !hasSelected;
                    }

                    if (!selectAll) {
                        return;
                    }

                    const checked = checkboxes.filter((checkbox) => checkbox.checked);
                    selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
                    selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
                    selectAll.disabled = checkboxes.length === 0;
                };

                toggle?.addEventListener('click', () => {
                    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                    const nextExpandedState = !isExpanded;
                    const icon = toggle.querySelector('[data-toggle-icon]');

                    toggle.setAttribute('aria-expanded', nextExpandedState.toString());
                    toggle.setAttribute(
                        'title',
                        nextExpandedState ? 'Collapse subjects' : 'Expand subjects'
                    );
                    toggle.setAttribute(
                        'aria-label',
                        `${nextExpandedState ? 'Collapse' : 'Expand'} subjects for this section`
                    );
                    panel?.classList.toggle('hidden', !nextExpandedState);
                    icon?.classList.toggle('rotate-180', !nextExpandedState);
                });

                selectAll?.addEventListener('change', () => {
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = selectAll.checked;
                    });

                    updateBulkButton();
                });

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', updateBulkButton);
                });

                updateBulkButton();
            });
        });
    </script>

</x-layout>
