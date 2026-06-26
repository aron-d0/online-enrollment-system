<?php

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

$subjectApiData = function (Subject $subject): array {
    return [
        'id' => $subject->id,
        'section_id' => $subject->section_id,
        'section' => $subject->section,
        'code' => $subject->code,
        'title' => $subject->title,
        'units' => $subject->units,
        'days' => $subject->days,
        'time_from' => $subject->time_from,
        'time_to' => $subject->time_to,
        'time_from_display' => $subject->timeFromForDisplay(),
        'time_to_display' => $subject->timeToForDisplay(),
        'room' => $subject->room,
        'schedule' => $subject->schedule,
        'schedule_display' => $subject->scheduleForDisplay(),
        'enrollments_count' => $subject->enrollments_count,
        'created_at' => $subject->created_at,
        'updated_at' => $subject->updated_at,
    ];
};

Route::get('/students', function () {
    return Student::with('user')
        ->orderBy('student_number')
        ->get();
});

Route::get('/students/{student}', function (Student $student) {
    return $student->load([
        'user',
        'enrollments.subject.section',
    ]);
});

Route::get('/sections', function () {
    return Section::withCount('subjects')
        ->orderBy('school_year', 'desc')
        ->orderByRaw("CASE semester WHEN '1st Semester' THEN 1 WHEN '2nd Semester' THEN 2 WHEN 'Summer' THEN 3 ELSE 4 END")
        ->orderBy('name')
        ->get();
});

Route::get('/sections/{section}/subjects', function (Section $section) use ($subjectApiData) {
    return $section->subjects()
        ->withCount('enrollments')
        ->orderBy('code')
        ->get()
        ->map(fn (Subject $subject) => $subjectApiData($subject));
});

Route::get('/sections/{section}', function (Section $section) {
    return $section->load([
        'subjects' => fn ($query) => $query->withCount('enrollments')->orderBy('code'),
    ]);
});

Route::get('/subjects', function () use ($subjectApiData) {
    return Subject::with('section')
        ->withCount('enrollments')
        ->join('sections', 'subjects.section_id', '=', 'sections.id')
        ->select('subjects.*')
        ->orderBy('sections.school_year', 'desc')
        ->orderByRaw("CASE sections.semester WHEN '1st Semester' THEN 1 WHEN '2nd Semester' THEN 2 WHEN 'Summer' THEN 3 ELSE 4 END")
        ->orderBy('sections.name')
        ->orderBy('subjects.code')
        ->get()
        ->map(fn (Subject $subject) => $subjectApiData($subject));
});

Route::get('/subjects/{subject}', function (Subject $subject) use ($subjectApiData) {
    return $subjectApiData(
        $subject->load('section')->loadCount('enrollments')
    );
});

Route::get('/enrollments', function () {
    return Enrollment::with([
        'student.user',
        'subject.section',
    ])
        ->latest()
        ->get();
});

Route::post('/enrollments', function (Request $request) {
    $validated = $request->validate([
        'student_id' => [
            'required',
            'integer',
            'exists:students,id',
        ],
        'subject_id' => [
            'required',
            'integer',
            'exists:subjects,id',
            Rule::unique('enrollments', 'subject_id')
                ->where('student_id', $request->input('student_id')),
        ],
        'status' => [
            'nullable',
            Rule::in([
                'Pending',
                'Approved',
                'Rejected',
            ]),
        ],
    ], [
        'subject_id.unique' => 'This student is already enrolled in that subject.',
    ]);

    $enrollment = Enrollment::create([
        'student_id' => $validated['student_id'],
        'subject_id' => $validated['subject_id'],
        'status' => $validated['status'] ?? 'Pending',
    ]);

    return response()->json(
        $enrollment->load([
            'student.user',
            'subject.section',
        ]),
        201
    );
});

Route::get('/enrollments/{enrollment}', function (Enrollment $enrollment) {
    return $enrollment->load([
        'student.user',
        'subject.section',
    ]);
});

Route::put('/enrollments/{enrollment}', function (Request $request, Enrollment $enrollment) {
    $validated = $request->validate([
        'status' => [
            'required',
            Rule::in([
                'Pending',
                'Approved',
                'Rejected',
            ]),
        ],
    ]);

    $enrollment->update([
        'status' => $validated['status'],
    ]);

    return $enrollment->load([
        'student.user',
        'subject.section',
    ]);
});

Route::delete('/enrollments/{enrollment}', function (Enrollment $enrollment) {
    $enrollment->delete();

    return response()->json([
        'message' => 'Enrollment deleted successfully.',
    ]);
});
