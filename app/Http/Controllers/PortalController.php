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

        $sections = Section::all();

        $subjects = [];

        $enrollments = Enrollment::with('subject')
            ->where(
                'student_id',
                $student->id
            )
            ->get();

        $isEnrolled = $enrollments->count() > 0;

        if ($request->section_id) {

            $subjects = Subject::where(
                'section_id',
                $request->section_id
            )->get();

        }

        return view('portal.index', compact(
            'student',
            'sections',
            'subjects',
            'isEnrolled',
            'enrollments'
        ));
    }
}