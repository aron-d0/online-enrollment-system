<?php

use Illuminate\Support\Facades\Route;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Enrollment;

Route::get('/students', function () {
    return Student::with('user')->get();
});

Route::get('/students/{student}', function (Student $student) {
    return response()->json($student);
});

Route::get('/subjects', function () {
    return Subject::all();
});

Route::get('/subjects/{subject}', function (Subject $subject) {
    return response()->json($subject);
});

Route::get('/enrollments', function () {
    return Enrollment::with([
        'student.user',
        'subject'
    ])->get();
});

Route::post('/enrollments', function (\Illuminate\Http\Request $request) {

    return Enrollment::create([
        'student_id' => $request->student_id,
        'subject_id' => $request->subject_id,
        'status' => 'Approved'
    ]);
});

Route::get('/enrollments/{enrollment}', function (Enrollment $enrollment) {
    return response()->json($enrollment);
});

Route::put('/enrollments/{enrollment}', function (\Illuminate\Http\Request $request, Enrollment $enrollment) {

    $enrollment->update([
        'status' => $request->status
    ]);

    return $enrollment;
});

Route::delete('/enrollments/{enrollment}', function (Enrollment $enrollment) {

    $enrollment->delete();

    return response()->json([
        'message' => 'Deleted successfully'
    ]);
});