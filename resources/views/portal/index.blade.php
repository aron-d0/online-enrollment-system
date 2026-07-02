<x-layout title="Student Portal">
@if(session('success'))

    <div class="mb-6 p-4 rounded-2xl
    bg-emerald-500/10 border border-emerald-500/20
    text-emerald-300">

        <i class="fa-solid fa-circle-check mr-2"></i>

        {{ session('success') }}

    </div>

@endif

@if(session('error'))

    <div class="mb-6 p-4 rounded-2xl
    bg-red-500/10 border border-red-500/20
    text-red-300">

        <i class="fa-solid fa-triangle-exclamation mr-2"></i>

        {{ session('error') }}

    </div>

@endif

@if($errors->any())

    <div class="mb-6 p-4 rounded-2xl
    bg-red-500/10 border border-red-500/20
    text-red-300">

        <i class="fa-solid fa-triangle-exclamation mr-2"></i>

        {{ $errors->first() }}

    </div>

@endif

<!-- Header -->

<div class="mb-8">

    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
    bg-cyan-500/10 border border-cyan-500/20 text-cyan-300 text-sm">

        <i class="fa-solid fa-user-graduate"></i>
        Student Portal

    </div>

    <h1 class="mt-4 text-5xl font-bold text-white">
        Enrollment Portal
    </h1>

    <p class="mt-2 text-slate-400">
        Welcome, {{ auth()->user()->name }}
    </p>

</div>

<!-- Student Information -->

<div class="bg-white/5 backdrop-blur-2xl border border-white/10
rounded-[32px] p-8 mb-8">

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">

        <div>

            <h2 class="text-2xl font-bold text-white mb-4">
                Student Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <p class="text-slate-400 text-sm">
                        Student Number
                    </p>

                    <p class="text-white font-semibold">
                        {{ $student->student_number }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-sm">
                        Name
                    </p>

                    <p class="text-white font-semibold">
                        {{ auth()->user()->name }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-sm">
                        Course
                    </p>

                    <p class="text-white font-semibold">
                        {{ $student->course }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-sm">
                        Year Level
                    </p>

                    <p class="text-white font-semibold">
                        {{ $student->year_level }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-sm">
                        Enrolled Section
                    </p>

                    <p class="text-white font-semibold">
                        {{ $enrolledSectionLabel }}
                    </p>
                </div>

                <div>
                    <p class="text-slate-400 text-sm">
                        Term
                    </p>

                    <p class="text-white font-semibold">
                        {{ $enrolledTermLabel }}
                    </p>
                </div>

            </div>

        </div>

        <div>

            @if($isEnrolled)

                <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full
                bg-emerald-500/20 text-emerald-300">

                    <i class="fa-solid fa-circle-check"></i>
                    ENROLLED

                </span>

            @else

                <span class="inline-flex items-center gap-2 px-5 py-3 rounded-full
                bg-yellow-500/20 text-yellow-300">

                    <i class="fa-solid fa-clock"></i>
                    NOT ENROLLED

                </span>

            @endif

        </div>

    </div>

</div>

<!-- Current Enrolled Subjects -->

<div class="mb-8">

    <h2 class="text-2xl font-bold text-white mb-4">
        Current Enrolled Subjects
    </h2>

    <div class="overflow-hidden rounded-[32px]
    bg-white/5 backdrop-blur-2xl
    border border-white/10">

        <table class="w-full">

            <thead>

                <tr class="bg-white/5 border-b border-white/10">

                    <th class="px-6 py-4 text-left text-slate-300">
                        CODE
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        SUBJECT
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        SECTION
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        UNITS
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        FROM
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        TO
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        DAYS
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        ROOM
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        STATUS
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($enrollments as $enrollment)

                    <tr class="border-b border-white/5 hover:bg-white/5">

                        <td class="px-6 py-4 text-cyan-300">
                            {{ $enrollment->subject->code }}
                        </td>

                        <td class="px-6 py-4 text-white">
                            {{ $enrollment->subject->title }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            <div class="font-semibold text-slate-200">
                                {{ $enrollment->subject->section?->name ?? '—' }}
                            </div>

                            <div class="text-xs text-slate-500">
                                {{ $enrollment->subject->section?->semester ?? '—' }}
                                ·
                                {{ $enrollment->subject->section?->school_year ?? '—' }}
                            </div>
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $enrollment->subject->units }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $enrollment->subject->timeFromForDisplay() }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $enrollment->subject->timeToForDisplay() }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $enrollment->subject->days }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $enrollment->subject->room }}
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

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center py-10 text-slate-500">

                            No enrolled subjects yet.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- Section Selection -->

<div class="bg-white/5 backdrop-blur-2xl border border-white/10
rounded-[32px] p-6 mb-8">

    <form method="GET" action="{{ route('portal') }}"
        class="flex flex-col md:flex-row items-center gap-4"
        data-section-loader
        data-subjects-url-template="/portal/sections/__SECTION__/subjects">

        <label class="text-slate-300 font-medium">

            Select Section

        </label>

        <select name="section_id"
            data-section-select
            class="flex-1 bg-slate-800 border border-slate-700
            text-white rounded-2xl px-4 py-3">

            <option value="">
                -- Select Section --
            </option>

            @foreach($sections as $section)

                <option value="{{ $section->id }}"
                    {{ request('section_id') == $section->id ? 'selected' : '' }}>

                    {{ $section->name }}

                </option>

            @endforeach

        </select>

        <button type="submit"
            data-load-section-button
            class="px-6 py-3 rounded-2xl
            bg-blue-600 hover:bg-blue-700
            text-white font-semibold transition">

            Load Subjects

        </button>

    </form>

</div>

<!-- Available Subjects -->

<form method="POST" action="{{ route('enroll.store') }}"
    data-confirm-title="Finalize Enrollment"
    data-confirm-message="Are you sure you want to finalize enrollment?"
    data-confirm-button="Finalize"
    data-enrollment-form
    data-is-enrolled="{{ $isEnrolled ? 'true' : 'false' }}"
    data-section-loaded="{{ request('section_id') ? 'true' : 'false' }}"
    data-subject-count="{{ count($subjects) }}">

    @csrf

    <input type="hidden"
        name="section_id"
        value="{{ request('section_id') }}"
        data-selected-section-input>

    <div class="overflow-hidden rounded-[32px]
    bg-white/5 backdrop-blur-2xl
    border border-white/10">

        <table class="w-full">

            <thead>

                <tr class="bg-white/5 border-b border-white/10">

                    <th class="px-6 py-4 text-left text-slate-300">
                        STAT
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        CODE
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        SUBJECT
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        UNITS
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        FROM
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        TO
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        DAYS
                    </th>

                    <th class="px-6 py-4 text-left text-slate-300">
                        ROOM
                    </th>

                </tr>

            </thead>

            <tbody data-subjects-table-body>

                @forelse($subjects as $subject)

                    <tr class="border-b border-white/5 hover:bg-white/5">

                        <td class="px-6 py-4">

                            <input
                                type="checkbox"
                                name="subjects[]"
                                value="{{ $subject->id }}"
                                checked
                                {{ $isEnrolled ? 'disabled' : '' }}
                                class="w-5 h-5 rounded">

                        </td>

                        <td class="px-6 py-4 text-cyan-300">
                            {{ $subject->code }}
                        </td>

                        <td class="px-6 py-4 text-white">
                            {{ $subject->title }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $subject->units }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $subject->timeFromForDisplay() }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $subject->timeToForDisplay() }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $subject->days }}
                        </td>

                        <td class="px-6 py-4 text-slate-300">
                            {{ $subject->room }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="px-6 py-12 text-center text-slate-500" data-subjects-empty-message>

                            @if(request('section_id'))

                                No subjects found for this section.

                            @else

                                No section loaded yet. Select a section first, then load its subjects.

                            @endif

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="flex justify-end mt-6">

        @if($isEnrolled)

            <button disabled
                class="px-8 py-3 rounded-2xl
                bg-emerald-500/20
                text-emerald-300
                font-semibold">

                FINALIZED

            </button>

        @else

            <button type="submit"
                class="px-8 py-3 rounded-2xl
                bg-blue-600 hover:bg-blue-700
                text-white font-semibold transition">

                Finalize Enrollment

            </button>

        @endif

    </div>

</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const enrollmentForm = document.querySelector('[data-enrollment-form]');
        const sectionLoader = document.querySelector('[data-section-loader]');
        const sectionSelect = document.querySelector('[data-section-select]');
        const loadSectionButton = document.querySelector('[data-load-section-button]');
        const selectedSectionInput = document.querySelector('[data-selected-section-input]');
        const subjectsTableBody = document.querySelector('[data-subjects-table-body]');

        if (!enrollmentForm) {
            return;
        }

        const escapeHtml = (value) => String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

        const emptySubjectsRow = (message) => `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-slate-500" data-subjects-empty-message>
                    ${escapeHtml(message)}
                </td>
            </tr>
        `;

        const subjectRow = (subject, isEnrolled) => `
            <tr class="border-b border-white/5 hover:bg-white/5">
                <td class="px-6 py-4">
                    <input
                        type="checkbox"
                        name="subjects[]"
                        value="${escapeHtml(subject.id)}"
                        checked
                        ${isEnrolled ? 'disabled' : ''}
                        class="w-5 h-5 rounded">
                </td>
                <td class="px-6 py-4 text-cyan-300">${escapeHtml(subject.code)}</td>
                <td class="px-6 py-4 text-white">${escapeHtml(subject.title)}</td>
                <td class="px-6 py-4 text-slate-300">${escapeHtml(subject.units)}</td>
                <td class="px-6 py-4 text-slate-300">${escapeHtml(subject.time_from)}</td>
                <td class="px-6 py-4 text-slate-300">${escapeHtml(subject.time_to)}</td>
                <td class="px-6 py-4 text-slate-300">${escapeHtml(subject.days)}</td>
                <td class="px-6 py-4 text-slate-300">${escapeHtml(subject.room)}</td>
            </tr>
        `;

        sectionLoader?.addEventListener('submit', async (event) => {
            event.preventDefault();

            const sectionId = sectionSelect?.value;

            if (!sectionId) {
                enrollmentForm.dataset.sectionLoaded = 'false';
                enrollmentForm.dataset.subjectCount = '0';
                selectedSectionInput.value = '';
                subjectsTableBody.innerHTML = emptySubjectsRow('No section loaded yet. Select a section first, then load its subjects.');
                history.replaceState({}, '', window.location.pathname);
                return;
            }

            const originalButtonText = loadSectionButton.textContent;
            loadSectionButton.disabled = true;
            loadSectionButton.textContent = 'Loading...';

            try {
                const url = sectionLoader.dataset.subjectsUrlTemplate.replace('__SECTION__', encodeURIComponent(sectionId));
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error('Request failed');
                }

                const payload = await response.json();
                const subjects = payload.subjects ?? [];
                const isEnrolled = enrollmentForm.dataset.isEnrolled === 'true';

                enrollmentForm.dataset.sectionLoaded = 'true';
                enrollmentForm.dataset.subjectCount = subjects.length.toString();
                selectedSectionInput.value = sectionId;
                subjectsTableBody.innerHTML = subjects.length
                    ? subjects.map((subject) => subjectRow(subject, isEnrolled)).join('')
                    : emptySubjectsRow('No subjects found for this section.');

                history.replaceState({}, '', `${window.location.pathname}?section_id=${encodeURIComponent(sectionId)}`);
            } catch (error) {
                subjectsTableBody.innerHTML = emptySubjectsRow('Unable to load subjects. Please try again.');
            } finally {
                loadSectionButton.disabled = false;
                loadSectionButton.textContent = originalButtonText;
            }
        });

        enrollmentForm.addEventListener('submit', async (event) => {
            const sectionLoaded = enrollmentForm.dataset.sectionLoaded === 'true';
            const subjectCount = Number(enrollmentForm.dataset.subjectCount || 0);

            if (sectionLoaded && subjectCount > 0) {
                return;
            }

            event.preventDefault();
            event.stopImmediatePropagation();

            if (typeof window.confirmAction === 'function') {
                await window.confirmAction({
                    title: sectionLoaded ? 'No Subjects Available' : 'No Section Loaded Yet',
                    message: sectionLoaded
                        ? 'This section has no subjects available to finalize. Please choose another section or ask an admin to add subjects.'
                        : 'Please select a section and click Load Subjects before finalizing enrollment.',
                    confirmText: 'Got it',
                });
            }
        }, true);
    });
</script>
</x-layout>
