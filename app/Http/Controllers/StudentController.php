<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user')
            ->latest()
            ->get();

        return view(
            'students.index',
            compact('students')
        );
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $emailUsername = Str::lower(trim((string) $request->input('email_username')));

        $request->merge([
            'name' => Str::upper(trim((string) $request->input('name'))),
            'student_number' => Str::upper(trim((string) $request->input('student_number'))),
            'course' => trim((string) $request->input('course')),
            'email_username' => $emailUsername,
            'email' => $emailUsername.'@psu.edu.ph',
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => ['required', 'string', 'regex:/^\d{2}-[A-Z]{2}-\d{4}$/', 'unique:students,student_number'],
            'course' => ['required', 'string', Rule::in(['BSIT'])],
            'year_level' => ['required', 'integer', Rule::in([3])],
            'email_username' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9._%+-]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'student_number.regex' => 'Student number must follow the format 22-LN-1234: two digits, campus code, then four digits.',
        ]);

        if (User::whereRaw('LOWER(name) = ?', [Str::lower($request->name)])->exists()) {
            throw ValidationException::withMessages([
                'name' => 'An account with this name already exists.',
            ]);
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->forceFill([
                'role' => 'student',
            ])->save();

            $user->student()->create([
                'student_number' => $request->student_number,
                'course' => $request->course,
                'year_level' => $request->year_level,
            ]);
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Student account created successfully.');
    }

    public function show(Student $student)
    {
        //
    }

    public function edit(Student $student)
    {
        $student->load('user');

        return view(
            'students.edit',
            compact('student')
        );
    }

    public function update(Request $request, Student $student)
    {
        $student->load('user');

        $emailUsername = Str::lower(trim((string) $request->input('email_username')));

        $request->merge([
            'name' => Str::upper(trim((string) $request->input('name'))),
            'student_number' => Str::upper(trim((string) $request->input('student_number'))),
            'course' => trim((string) $request->input('course')),
            'email_username' => $emailUsername,
            'email' => $emailUsername.'@psu.edu.ph',
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => [
                'required',
                'string',
                'regex:/^\d{2}-[A-Z]{2}-\d{4}$/',
                Rule::unique('students', 'student_number')->ignore($student->id),
            ],
            'course' => ['required', 'string', Rule::in(['BSIT'])],
            'year_level' => ['required', 'integer', Rule::in([3])],
            'email_username' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9._%+-]+$/'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], [
            'student_number.regex' => 'Student number must follow the format 22-LN-1234: two digits, campus code, then four digits.',
        ]);

        if (
            User::whereRaw('LOWER(name) = ?', [Str::lower($request->name)])
                ->whereKeyNot($student->user_id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'name' => 'An account with this name already exists.',
            ]);
        }

        DB::transaction(function () use ($request, $student) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);

            $student->update([
                'student_number' => $request->student_number,
                'course' => $request->course,
                'year_level' => $request->year_level,
            ]);
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Student record updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->load('user');

        DB::transaction(function () use ($student) {
            $student->user->delete();
        });

        return redirect()
            ->route('students.index')
            ->with('success', 'Student account deleted successfully.');
    }
}
