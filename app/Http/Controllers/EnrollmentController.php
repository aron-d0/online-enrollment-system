<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with([
            'student.user',
            'subject'
        ])
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('enrollments.*')
            ->orderBy('users.name')
            ->orderBy('students.student_number')
            ->orderBy('enrollments.created_at')
            ->get();

        return view(
            'enrollments.index',
            compact('enrollments')
        );
    }

    public function store(Request $request)
    {
        $student = auth()->user()->student;

        $subjectIds = $request->subjects ?? [];

        foreach ($subjectIds as $subjectId) {

            Enrollment::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                ],
                [
                    'status' => 'Pending'
                ]
            );
        }

        return redirect()
            ->route(
                'portal',
                ['section_id' => $request->section_id]
            )
            ->with(
                'success',
                'Enrollment completed successfully.'
            );
    }

    public function exportJson()
    {
        $enrollments = $this->enrollmentExportQuery();

        $students = $enrollments
            ->groupBy('student_id')
            ->values()
            ->map(function ($studentEnrollments) {
                $student = $studentEnrollments->first()->student;
                $user = $student->user;

                return [
                    'student_number' => $student->student_number,
                    'student_name' => $user->name,
                    'email' => $user->email,
                    'course' => $student->course,
                    'year_level' => $student->year_level,
                    'enrollments' => $studentEnrollments->map(function ($enrollment) {
                        return [
                            'subject_code' => $enrollment->subject->code,
                            'subject_title' => $enrollment->subject->title,
                            'units' => $enrollment->subject->units,
                            'schedule' => $enrollment->subject->scheduleForDisplay(),
                            'section' => $enrollment->subject->section?->name,
                            'semester' => $enrollment->subject->section?->semester,
                            'school_year' => $enrollment->subject->section?->school_year,
                            'status' => $enrollment->status,
                            'enrolled_at' => $enrollment->created_at?->format('Y-m-d H:i:s'),
                        ];
                    })->values(),
                ];
            });

        $report = [
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'total_students' => $students->count(),
            'total_enrollments' => $enrollments->count(),
            'totals_by_status' => [
                'pending' => $enrollments->where('status', 'Pending')->count(),
                'approved' => $enrollments->where('status', 'Approved')->count(),
                'rejected' => $enrollments->where('status', 'Rejected')->count(),
            ],
            'students' => $students,
        ];

        return response(
            json_encode($report, JSON_PRETTY_PRINT)
        )
            ->header(
                'Content-Type',
                'application/json'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="enrollments_' . now()->format('Y-m-d') . '.json"'
            );
    }

    public function exportCsv()
    {
        $enrollments = $this->enrollmentExportQuery();

        $response = new StreamedResponse(function () use ($enrollments) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Student Number',
                'Student Name',
                'Email',
                'Course',
                'Year Level',
                'Section',
                'Semester',
                'School Year',
                'Subject Code',
                'Subject Title',
                'Schedule',
                'Units',
                'Status',
                'Enrolled At'
            ]);

            foreach ($enrollments as $enrollment) {

                fputcsv($handle, [

                    $enrollment->student->student_number,

                    $enrollment->student->user->name,

                    $enrollment->student->user->email,

                    $enrollment->student->course,

                    $enrollment->student->year_level,

                    $enrollment->subject->section?->name,

                    $enrollment->subject->section?->semester,

                    $enrollment->subject->section?->school_year,

                    $enrollment->subject->code,

                    $enrollment->subject->title,

                    $enrollment->subject->scheduleForDisplay(),

                    $enrollment->subject->units,

                    $enrollment->status,

                    $enrollment->created_at?->format('Y-m-d H:i:s'),

                ]);
            }

            fclose($handle);
        });

        $response->headers->set(
            'Content-Type',
            'text/csv; charset=UTF-8'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="enrollments_' . now()->format('Y-m-d') . '.csv"'
        );

        return $response;
    }

    private function enrollmentExportQuery()
    {
        return Enrollment::with([
            'student.user',
            'subject.section'
        ])
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->select('enrollments.*')
            ->orderBy('users.name')
            ->orderBy('students.student_number')
            ->orderBy('subjects.code')
            ->get();
    }

    public function approve(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'Approved'
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Enrollment approved successfully.',
                'status' => $enrollment->status,
            ]);
        }

        return back()->with(
            'success',
            'Enrollment approved successfully.'
        );
    }

    public function reject(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'Rejected'
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Enrollment rejected successfully.',
                'status' => $enrollment->status,
            ]);
        }

        return back()->with(
            'success',
            'Enrollment rejected successfully.'
        );
    }

    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'enrollment_ids' => ['required', 'array', 'min:1'],
            'enrollment_ids.*' => ['integer', 'exists:enrollments,id'],
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        Enrollment::whereIn('id', $validated['enrollment_ids'])
            ->update([
                'status' => $validated['status']
            ]);

        $message = $validated['status'] === 'Approved'
            ? 'Selected enrollments approved successfully.'
            : 'Selected enrollments rejected successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'status' => $validated['status'],
                'enrollment_ids' => $validated['enrollment_ids'],
            ]);
        }

        return back()->with('success', $message);
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'enrollment_ids' => ['required', 'array', 'min:1'],
            'enrollment_ids.*' => ['integer', 'exists:enrollments,id'],
        ]);

        Enrollment::whereIn('id', $validated['enrollment_ids'])
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Selected enrollments deleted successfully.',
                'enrollment_ids' => $validated['enrollment_ids'],
            ]);
        }

        return back()->with(
            'success',
            'Selected enrollments deleted successfully.'
        );
    }

    public function destroy(Enrollment $enrollment)
    {
        $id = $enrollment->id;

        $enrollment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Enrollment deleted successfully.',
                'enrollment_id' => $id,
            ]);
        }

        return back()->with(
            'success',
            'Enrollment deleted successfully.'
        );
    }
}
