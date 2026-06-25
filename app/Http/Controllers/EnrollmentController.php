<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnrollmentController extends Controller
{
    // public function create()
    // {
    //     $students = Student::with('user')->get();

    //     $sections = Section::all();

    //     return view(
    //         'enrollments.create',
    //         compact(
    //             'students',
    //             'sections'
    //         )
    //     );
    // }
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
    // public function reset($studentId)
    // {
    //     Enrollment::where(
    //         'student_id',
    //         $studentId
    //     )->delete();

    //     return redirect()
    //         ->route('enrollments.index')
    //         ->with(
    //             'success',
    //             'Enrollment reset successfully.'
    //         );
    // }
    public function exportJson()
    {
        $enrollments = Enrollment::with([
            'student.user',
            'subject'
        ])->get();

        return response(
            $enrollments->toJson(
                JSON_PRETTY_PRINT
            )
        )
            ->header(
                'Content-Type',
                'application/json'
            )
            ->header(
                'Content-Disposition',
                'attachment; filename="enrollment_report.json"'
            );
    }

    public function exportCsv()
    {
        $enrollments = Enrollment::with([
            'student.user',
            'subject'
        ])->get();

        $response = new StreamedResponse(function () use ($enrollments) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Student Number',
                'Student Name',
                'Subject Code',
                'Subject',
                'Units',
                'Status'
            ]);

            foreach ($enrollments as $enrollment) {

                fputcsv($handle, [

                    $enrollment->student->student_number,

                    $enrollment->student->user->name,

                    $enrollment->subject->code,

                    $enrollment->subject->title,

                    $enrollment->subject->units,

                    $enrollment->status,

                ]);
            }

            fclose($handle);
        });

        $response->headers->set(
            'Content-Type',
            'text/csv'
        );

        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="enrollment_report.csv"'
        );

        return $response;
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
        $enrollment->delete();

        return back()->with(
            'success',
            'Enrollment deleted successfully.'
        );
    }
}
