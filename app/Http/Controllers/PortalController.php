<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->user()->student;

        $sections = Section::orderBy('school_year', 'desc')
            ->orderBy('name')
            ->get();

        $subjects = [];

        $enrollments = Enrollment::with('subject.section')
            ->where(
                'student_id',
                $student->id
            )
            ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->select('enrollments.*')
            ->orderBy('subjects.code')
            ->get();

        $isEnrolled = $enrollments->count() > 0;

        $enrolledSections = $enrollments
            ->pluck('subject.section')
            ->filter()
            ->unique('id')
            ->values();

        $enrolledSectionLabel = $enrolledSections->count() === 1
            ? $enrolledSections->first()->name
            : ($enrolledSections->count() > 1 ? 'Multiple Sections' : '—');

        $enrolledTermLabel = $enrolledSections->count() === 1
            ? $enrolledSections->first()->semester . ' · ' . $enrolledSections->first()->school_year
            : ($enrolledSections->count() > 1 ? 'Multiple Terms' : '—');

        if ($request->section_id) {

            $subjects = Subject::where(
                'section_id',
                $request->section_id
            )
                ->orderBy('code')
                ->get();

        }

        return view('portal.index', compact(
            'student',
            'sections',
            'subjects',
            'isEnrolled',
            'enrollments',
            'enrolledSectionLabel',
            'enrolledTermLabel'
        ));
    }
}
