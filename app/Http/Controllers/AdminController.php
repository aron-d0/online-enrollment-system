<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Enrollment;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [

            'studentCount' => Student::count(),

            'subjectCount' => Subject::count(),

            'sectionCount' => Section::count(),

            'enrollmentCount' => Enrollment::count()

        ]);
    }
}