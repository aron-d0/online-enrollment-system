<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
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
        ])->get();

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
    // public function adminStore(Request $request)
    // {
    //     $student = Student::findOrFail(
    //         $request->student_id
    //     );

    //     $subjectIds = $request->subjects ?? [];

    //     foreach ($subjectIds as $subjectId) {

    //         Enrollment::firstOrCreate(
    //             [
    //                 'student_id' => $student->id,
    //                 'subject_id' => $subjectId,
    //             ],
    //             [
    //                 'status' => 'Pending'
    //             ]
    //         );
    //     }

    //     return redirect()
    //         ->route('enrollments.index')
    //         ->with(
    //             'success',
    //             'Student enrolled successfully.'
    //         );
    // }
    public function reset($studentId)
    {
        Enrollment::where(
            'student_id',
            $studentId
        )->delete();

        return redirect()
            ->route('enrollments.index')
            ->with(
                'success',
                'Enrollment reset successfully.'
            );
    }
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

        return back();
    }

    public function reject(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'Rejected'
        ]);

        return back();
    }
}