<x-layout title="Enrollments">

    @php
        $enrollmentsByStudent = $enrollments->groupBy('student_id');
        $pendingCount = $enrollments->where('status', 'Pending')->count();
        $approvedCount = $enrollments->where('status', 'Approved')->count();
        $rejectedCount = $enrollments->where('status', 'Rejected')->count();
    @endphp

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6 mb-8">

        <div>

            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
            bg-purple-500/10 border border-purple-500/20 text-purple-300 text-sm">

                <i class="fa-solid fa-file-signature"></i>
                Student Enrollment Requests

            </div>

            <h1 class="mt-4 text-5xl font-bold text-white">
                Enrollments
            </h1>

            <p class="mt-2 text-slate-400">
                Review, approve, reject, and manage student subject enrollments
            </p>

        </div>

        <div class="flex flex-wrap gap-3">

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

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

        <div class="rounded-3xl border border-white/10 bg-white/5 px-6 py-5">
            <p class="text-sm text-slate-400">Students</p>
            <p class="mt-1 text-3xl font-bold text-white" data-summary-count="students">{{ $enrollmentsByStudent->count() }}</p>
        </div>

        <div class="rounded-3xl border border-yellow-500/20 bg-yellow-500/10 px-6 py-5">
            <p class="text-sm text-yellow-300">Pending</p>
            <p class="mt-1 text-3xl font-bold text-white" data-summary-count="Pending">{{ $pendingCount }}</p>
        </div>

        <div class="rounded-3xl border border-emerald-500/20 bg-emerald-500/10 px-6 py-5">
            <p class="text-sm text-emerald-300">Approved</p>
            <p class="mt-1 text-3xl font-bold text-white" data-summary-count="Approved">{{ $approvedCount }}</p>
        </div>

        <div class="rounded-3xl border border-red-500/20 bg-red-500/10 px-6 py-5">
            <p class="text-sm text-red-300">Rejected</p>
            <p class="mt-1 text-3xl font-bold text-white" data-summary-count="Rejected">{{ $rejectedCount }}</p>
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

    <div id="enrollmentToast"
        class="fixed right-6 top-6 z-[9999] hidden rounded-2xl border border-emerald-500/20 bg-slate-900/95 px-5 py-4 text-emerald-300 shadow-2xl backdrop-blur-xl">
        <i class="fa-solid fa-circle-check mr-2"></i>
        <span data-toast-message>Enrollment updated.</span>
    </div>

    <div class="space-y-8">

        @forelse($enrollmentsByStudent as $studentEnrollments)

            @php
                $student = $studentEnrollments->first()->student;
                $studentSections = $studentEnrollments
                    ->pluck('subject.section')
                    ->filter()
                    ->unique('id')
                    ->values();

                $studentSectionLabel = $studentSections->count() === 1
                    ? $studentSections->first()->name
                    : ($studentSections->count() > 1 ? 'Multiple Sections' : '—');

                $studentTermLabel = $studentSections->count() === 1
                    ? $studentSections->first()->semester . ' · ' . $studentSections->first()->school_year
                    : ($studentSections->count() > 1 ? 'Multiple Terms' : '—');
            @endphp

            <section class="overflow-hidden rounded-[32px]
                bg-white/5 backdrop-blur-2xl
                border border-white/10
                shadow-[0_20px_60px_rgba(0,0,0,.25)]"
                data-student-enrollment-group>

                <div class="grid gap-5 border-b border-white/10 bg-white/5 px-6 py-5 xl:grid-cols-[minmax(280px,1fr)_auto] xl:items-start">

                    <div class="flex min-w-0 items-center gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-500/15 text-purple-300">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-xl font-bold text-white">
                                {{ $student->user->name }}
                            </h2>

                            <p class="text-sm text-slate-400">
                                {{ $student->student_number }} · {{ $student->course }} · Year {{ $student->year_level }}
                            </p>

                            <p class="mt-1 text-sm text-cyan-300">
                                <i class="fa-solid fa-layer-group mr-1"></i>
                                Section: {{ $studentSectionLabel }}
                                <span class="text-slate-500">·</span>
                                {{ $studentTermLabel }}
                            </p>
                        </div>

                    </div>

                    <div class="flex flex-col gap-3 xl:items-end">

                        <div class="flex flex-wrap items-center gap-2 text-sm xl:justify-end">

                            <span class="rounded-full bg-white/5 px-4 py-2 text-slate-300">
                                <span data-student-count="total">{{ $studentEnrollments->count() }}</span> Subjects
                            </span>

                            <span class="rounded-full bg-cyan-500/10 px-4 py-2 text-cyan-300">
                                {{ $studentSectionLabel }}
                            </span>

                            <span class="rounded-full bg-yellow-500/10 px-4 py-2 text-yellow-300">
                                <span data-student-count="Pending">{{ $studentEnrollments->where('status', 'Pending')->count() }}</span> Pending
                            </span>

                            <span class="rounded-full bg-emerald-500/10 px-4 py-2 text-emerald-300">
                                <span data-student-count="Approved">{{ $studentEnrollments->where('status', 'Approved')->count() }}</span> Approved
                            </span>

                            <span class="rounded-full bg-red-500/10 px-4 py-2 text-red-300">
                                <span data-student-count="Rejected">{{ $studentEnrollments->where('status', 'Rejected')->count() }}</span> Rejected
                            </span>

                        </div>

                        <div class="flex flex-wrap items-center gap-2 xl:justify-end">

                            <button type="button"
                            data-remove-student-enrollments
                            data-bulk-url="{{ route('enrollments.bulk-destroy', absolute: false) }}"
                                class="rounded-full border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300 transition hover:bg-red-500/20">
                                <i class="fa-solid fa-trash mr-2"></i>
                                Remove Student Enrollment
                            </button>

                            <button type="button"
                                data-enrollment-toggle
                                aria-expanded="false"
                                title="Expand subjects"
                                aria-label="Expand subjects for {{ $student->user->name }}"
                                class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10">
                                <i class="fa-solid fa-chevron-up rotate-180 transition-transform duration-200" data-toggle-icon></i>
                            </button>

                        </div>

                    </div>

                </div>

                <div data-enrollment-panel class="hidden">

                <div class="flex flex-col gap-3 border-b border-white/10 px-6 py-4 md:flex-row md:items-center md:justify-between">

                    <p class="text-sm text-slate-400">
                        Select subjects below, then apply an action to this student's checked enrollments.
                    </p>

                    <div class="flex flex-wrap gap-2">

                        <button type="button"
                            data-bulk-action="Approved"
                            data-bulk-url="{{ route('enrollments.bulk-status', absolute: false) }}"
                            disabled
                            class="bulk-action-button h-10 px-4 rounded-full bg-emerald-500/15 border border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/25 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-check-double mr-2"></i>
                            Approve Selected
                        </button>

                        <button type="button"
                            data-bulk-action="Rejected"
                            data-bulk-url="{{ route('enrollments.bulk-status', absolute: false) }}"
                            disabled
                            class="bulk-action-button h-10 px-4 rounded-full bg-red-500/15 border border-red-500/20 text-red-300 hover:bg-red-500/25 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-xmark mr-2"></i>
                            Reject Selected
                        </button>

                        <button type="button"
                            data-bulk-action="Delete"
                            data-bulk-url="{{ route('enrollments.bulk-destroy', absolute: false) }}"
                            disabled
                            class="bulk-action-button h-10 px-4 rounded-full bg-slate-700/50 border border-slate-600 text-slate-300 hover:bg-slate-600 transition disabled:opacity-40 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Delete Selected
                        </button>

                    </div>

                </div>

                <div class="overflow-hidden">

                    <table class="w-full table-fixed text-sm">

                        <colgroup>
                            <col class="w-[4%]">
                            <col class="w-[9%]">
                            <col class="w-[23%]">
                            <col class="w-[6%]">
                            <col class="w-[8%]">
                            <col class="w-[8%]">
                            <col class="w-[10%]">
                            <col class="w-[9%]">
                            <col class="w-[10%]">
                            <col class="w-[13%]">
                        </colgroup>

                        <thead>

                            <tr class="border-b border-white/10">

                                <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                    <input type="checkbox"
                                        class="student-select-all rounded border-slate-600 bg-slate-900 text-blue-500 focus:ring-blue-500"
                                        aria-label="Select all enrollments for {{ $student->user->name }}">
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
                                    Status
                                </th>

                                <th class="px-3 py-4 text-left text-slate-300 font-semibold">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($studentEnrollments as $enrollment)

                                <tr class="border-b border-white/5 hover:bg-white/5 transition"
                                    data-enrollment-row="{{ $enrollment->id }}"
                                    data-status="{{ $enrollment->status }}">

                                    <td class="px-3 py-3">
                                        <input type="checkbox"
                                            class="enrollment-checkbox rounded border-slate-600 bg-slate-900 text-blue-500 focus:ring-blue-500"
                                            value="{{ $enrollment->id }}"
                                            aria-label="Select {{ $enrollment->subject->code }}">
                                    </td>

                                    <td class="px-3 py-3 text-cyan-300 font-medium break-words">
                                        {{ $enrollment->subject->code }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-200 leading-snug">
                                        {{ $enrollment->subject->title }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-200">
                                        {{ $enrollment->subject->units }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-300">
                                        {{ $enrollment->subject->timeFromForDisplay() }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-300">
                                        {{ $enrollment->subject->timeToForDisplay() }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-300 break-words">
                                        {{ $enrollment->subject->days ?? '—' }}
                                    </td>

                                    <td class="px-3 py-3 text-slate-300 break-words">
                                        {{ $enrollment->subject->room ?? '—' }}
                                    </td>

                                    <td class="px-3 py-3">
                                        <span data-status-badge>
                                            @if($enrollment->status === 'Approved')
                                                <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs">
                                                    Approved
                                                </span>
                                            @elseif($enrollment->status === 'Rejected')
                                                <span class="px-2.5 py-1 rounded-full bg-red-500/20 text-red-300 text-xs">
                                                    Rejected
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full bg-yellow-500/20 text-yellow-300 text-xs">
                                                    Pending
                                                </span>
                                            @endif
                                        </span>
                                    </td>

                                    <td class="px-3 py-3">

                                        <div class="flex flex-wrap items-center gap-1.5">

                                            <div class="flex items-center gap-1.5" data-approval-actions>

                                                @if($enrollment->status === 'Pending')

                                                    <button type="button"
                                                        data-status-url="{{ route('enrollments.approve', $enrollment, absolute: false) }}"
                                                        data-status-action="Approved"
                                                        title="Approve"
                                                        aria-label="Approve {{ $enrollment->subject->code }}"
                                                        class="flex h-9 w-9 items-center justify-center rounded-full
                                                        bg-emerald-500/15 border border-emerald-500/20
                                                        text-emerald-300 hover:bg-emerald-500/25
                                                        transition-all duration-200 hover:scale-105">

                                                        <i class="fa-solid fa-check"></i>
                                                        <span class="sr-only">Approve</span>

                                                    </button>

                                                    <button type="button"
                                                        data-status-url="{{ route('enrollments.reject', $enrollment, absolute: false) }}"
                                                        data-status-action="Rejected"
                                                        title="Reject"
                                                        aria-label="Reject {{ $enrollment->subject->code }}"
                                                        class="flex h-9 w-9 items-center justify-center rounded-full
                                                        bg-red-500/15 border border-red-500/20
                                                        text-red-300 hover:bg-red-500/25
                                                        transition-all duration-200 hover:scale-105">

                                                        <i class="fa-solid fa-xmark"></i>
                                                        <span class="sr-only">Reject</span>

                                                    </button>

                                                @endif

                                            </div>

                                            <button type="button"
                                                data-delete-url="{{ route('enrollments.destroy', $enrollment, absolute: false) }}"
                                                title="Delete"
                                                aria-label="Delete {{ $enrollment->subject->code }}"
                                                class="flex h-9 w-9 items-center justify-center rounded-full
                                                bg-slate-700/50 border border-slate-600
                                                text-slate-300 hover:bg-slate-600
                                                transition-all duration-200 hover:scale-105">

                                                <i class="fa-solid fa-trash"></i>
                                                <span class="sr-only">Delete</span>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                </div>

            </section>

        @empty

            <div class="rounded-[32px] border border-white/10 bg-white/5 p-12 text-center text-slate-400">
                No enrollments found.
            </div>

        @endforelse

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const toast = document.getElementById('enrollmentToast');
            let toastTimer;

            const statusBadge = (status) => {
                const classes = {
                    Approved: 'bg-emerald-500/20 text-emerald-300',
                    Rejected: 'bg-red-500/20 text-red-300',
                    Pending: 'bg-yellow-500/20 text-yellow-300',
                };

                return `<span class="px-2.5 py-1 rounded-full ${classes[status] ?? classes.Pending} text-xs">${status}</span>`;
            };

            const showToast = (message, type = 'success') => {
                if (!toast) {
                    return;
                }

                const messageTarget = toast.querySelector('[data-toast-message]');
                messageTarget.textContent = message;

                toast.classList.remove('hidden', 'text-emerald-300', 'text-red-300', 'border-emerald-500/20', 'border-red-500/20');
                toast.classList.add(
                    type === 'error' ? 'text-red-300' : 'text-emerald-300',
                    type === 'error' ? 'border-red-500/20' : 'border-emerald-500/20'
                );

                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => toast.classList.add('hidden'), 2400);
            };

            const selectedCheckboxes = (group) => {
                return Array.from(group.querySelectorAll('.enrollment-checkbox:checked'));
            };

            const selectedIds = (group) => {
                return selectedCheckboxes(group).map((checkbox) => checkbox.value);
            };

            const updateBulkButtons = (group) => {
                const hasSelected = selectedCheckboxes(group).length > 0;

                group.querySelectorAll('.bulk-action-button').forEach((button) => {
                    button.disabled = !hasSelected;
                });
            };

            const syncSelectAll = (group) => {
                const selectAll = group.querySelector('.student-select-all');
                const checkboxes = Array.from(group.querySelectorAll('.enrollment-checkbox'));
                const checked = checkboxes.filter((checkbox) => checkbox.checked);

                if (!selectAll) {
                    return;
                }

                selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
            };

            const adjustCount = (selector, delta) => {
                const target = document.querySelector(selector);

                if (!target) {
                    return;
                }

                target.textContent = Math.max(0, Number.parseInt(target.textContent, 10) + delta);
            };

            const adjustSummaryCount = (status, delta) => {
                adjustCount(`[data-summary-count="${status}"]`, delta);
            };

            const adjustStudentCount = (group, status, delta) => {
                const target = group.querySelector(`[data-student-count="${status}"]`);

                if (!target) {
                    return;
                }

                target.textContent = Math.max(0, Number.parseInt(target.textContent, 10) + delta);
            };

            const transitionRowStatus = (row, group, newStatus) => {
                const oldStatus = row.dataset.status;

                if (oldStatus === newStatus) {
                    return;
                }

                adjustSummaryCount(oldStatus, -1);
                adjustSummaryCount(newStatus, 1);
                adjustStudentCount(group, oldStatus, -1);
                adjustStudentCount(group, newStatus, 1);
                row.dataset.status = newStatus;
            };

            const removeEnrollmentRow = (row, group) => {
                const oldStatus = row.dataset.status;

                adjustSummaryCount(oldStatus, -1);
                adjustStudentCount(group, oldStatus, -1);
                adjustStudentCount(group, 'total', -1);

                row.remove();

                if (!group.querySelector('[data-enrollment-row]')) {
                    group.remove();
                    adjustSummaryCount('students', -1);
                }
            };

            document.querySelectorAll('[data-status-action]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const row = button.closest('[data-enrollment-row]');
                    const group = button.closest('[data-student-enrollment-group]');
                    const actions = row.querySelector('[data-approval-actions]');
                    const statusTarget = row.querySelector('[data-status-badge]');
                    const siblingButtons = actions.querySelectorAll('[data-status-action]');

                    siblingButtons.forEach((actionButton) => {
                        actionButton.disabled = true;
                        actionButton.classList.add('opacity-60', 'cursor-wait');
                    });

                    try {
                        const response = await fetch(button.dataset.statusUrl, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();
                        const status = payload.status ?? button.dataset.statusAction;

                        transitionRowStatus(row, group, status);
                        statusTarget.innerHTML = statusBadge(status);
                        actions.innerHTML = '';
                        row.querySelector('.enrollment-checkbox').checked = false;
                        row.classList.add('bg-white/5');
                        updateBulkButtons(group);
                        syncSelectAll(group);
                        showToast(payload.message ?? `Enrollment ${status.toLowerCase()} successfully.`);
                    } catch (error) {
                        siblingButtons.forEach((actionButton) => {
                            actionButton.disabled = false;
                            actionButton.classList.remove('opacity-60', 'cursor-wait');
                        });

                        showToast('Unable to update enrollment. Please try again.', 'error');
                    }
                });
            });

            document.querySelectorAll('[data-delete-url]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const confirmed = await window.confirmAction({
                        title: 'Delete Enrollment',
                        message: 'Delete this enrollment?',
                        confirmText: 'Delete',
                    });

                    if (!confirmed) {
                        return;
                    }

                    const row = button.closest('[data-enrollment-row]');
                    const group = button.closest('[data-student-enrollment-group]');

                    button.disabled = true;
                    button.classList.add('opacity-60', 'cursor-wait');

                    try {
                        const response = await fetch(button.dataset.deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();

                        removeEnrollmentRow(row, group);
                        updateBulkButtons(group);
                        syncSelectAll(group);
                        showToast(payload.message ?? 'Enrollment deleted successfully.');
                    } catch (error) {
                        button.disabled = false;
                        button.classList.remove('opacity-60', 'cursor-wait');
                        showToast('Unable to delete enrollment. Please try again.', 'error');
                    }
                });
            });

            document.querySelectorAll('[data-student-enrollment-group]').forEach((group) => {
                const selectAll = group.querySelector('.student-select-all');
                const toggle = group.querySelector('[data-enrollment-toggle]');
                const panel = group.querySelector('[data-enrollment-panel]');

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
                        `${nextExpandedState ? 'Collapse' : 'Expand'} subjects for this student`
                    );
                    panel?.classList.toggle('hidden', !nextExpandedState);

                    icon?.classList.toggle('rotate-180', !nextExpandedState);
                });

                selectAll?.addEventListener('change', () => {
                    group.querySelectorAll('.enrollment-checkbox').forEach((enrollmentCheckbox) => {
                        enrollmentCheckbox.checked = selectAll.checked;
                    });

                    updateBulkButtons(group);
                    syncSelectAll(group);
                });

                group.querySelectorAll('.enrollment-checkbox').forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        updateBulkButtons(group);
                        syncSelectAll(group);
                    });
                });

                group.querySelector('[data-remove-student-enrollments]')?.addEventListener('click', async (event) => {
                    const button = event.currentTarget;
                    const ids = Array.from(group.querySelectorAll('[data-enrollment-row]'))
                        .map((row) => row.dataset.enrollmentRow);

                    if (ids.length === 0) {
                        showToast('This student has no enrollments to remove.', 'error');
                        return;
                    }

                    const confirmed = await window.confirmAction({
                        title: 'Remove Student Enrollment',
                        message: 'Remove all subject enrollments for this student?',
                        confirmText: 'Remove',
                    });

                    if (!confirmed) {
                        return;
                    }

                    button.disabled = true;
                    button.classList.add('opacity-60', 'cursor-wait');

                    try {
                        const response = await fetch(button.dataset.bulkUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                enrollment_ids: ids,
                            }),
                        });

                        if (!response.ok) {
                            throw new Error('Request failed');
                        }

                        const payload = await response.json();

                        ids.forEach((id) => {
                            const row = group.querySelector(`[data-enrollment-row="${id}"]`);

                            if (row) {
                                removeEnrollmentRow(row, group);
                            }
                        });

                        showToast(payload.message ?? 'Student enrollment removed successfully.');
                    } catch (error) {
                        button.disabled = false;
                        button.classList.remove('opacity-60', 'cursor-wait');
                        showToast('Unable to remove student enrollment. Please try again.', 'error');
                    }
                });

                group.querySelectorAll('[data-bulk-action]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        const ids = selectedIds(group);

                        if (ids.length === 0) {
                            showToast('Select at least one enrollment first.', 'error');
                            return;
                        }

                        const action = button.dataset.bulkAction;

                        if (action === 'Delete') {
                            const confirmed = await window.confirmAction({
                                title: 'Delete Selected Enrollments',
                                message: 'Delete the selected enrollments?',
                                confirmText: 'Delete',
                            });

                            if (!confirmed) {
                                return;
                            }
                        }

                        group.querySelectorAll('.bulk-action-button').forEach((actionButton) => {
                            actionButton.disabled = true;
                            actionButton.classList.add('opacity-60', 'cursor-wait');
                        });

                        try {
                            const response = await fetch(button.dataset.bulkUrl, {
                                method: action === 'Delete' ? 'DELETE' : 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(
                                    action === 'Delete'
                                        ? { enrollment_ids: ids }
                                        : { enrollment_ids: ids, status: action }
                                ),
                            });

                            if (!response.ok) {
                                throw new Error('Request failed');
                            }

                            const payload = await response.json();

                            ids.forEach((id) => {
                                const row = group.querySelector(`[data-enrollment-row="${id}"]`);

                                if (!row) {
                                    return;
                                }

                                if (action === 'Delete') {
                                    removeEnrollmentRow(row, group);
                                    return;
                                }

                                transitionRowStatus(row, group, payload.status ?? action);
                                row.querySelector('[data-status-badge]').innerHTML = statusBadge(payload.status ?? action);
                                row.querySelector('[data-approval-actions]').innerHTML = '';
                                row.querySelector('.enrollment-checkbox').checked = false;
                                row.classList.add('bg-white/5');
                            });

                            updateBulkButtons(group);
                            syncSelectAll(group);
                            showToast(payload.message ?? 'Selected enrollments updated successfully.');
                        } catch (error) {
                            showToast('Unable to update selected enrollments. Please try again.', 'error');
                        } finally {
                            updateBulkButtons(group);

                            group.querySelectorAll('.bulk-action-button').forEach((actionButton) => {
                                actionButton.classList.remove('opacity-60', 'cursor-wait');
                            });
                        }
                    });
                });
            });
        });
    </script>

</x-layout>
