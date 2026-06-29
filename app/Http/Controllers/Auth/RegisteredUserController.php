<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Display the registration confirmation view.
     */
    public function confirmation(): View
    {
        return view('auth.register-confirmation');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
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
            'name' => ['required', 'string', 'max:255', "regex:/^[A-Z]+(?:[ .'-][A-Z]+)*$/"],
            'student_number' => ['required', 'string', 'regex:/^\d{2}-[A-Z]{2}-\d{4}$/'],
            'course' => ['required', 'string', Rule::in(['BSIT'])],
            'year_level' => ['required', 'integer', Rule::in([3])],
            'email_username' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9._%+-]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.regex' => 'Full name may only contain letters, spaces, periods, hyphens, and apostrophes.',
            'student_number.regex' => 'Use the specified format: 22-LN-1234.',
            'email_username.regex' => 'Email may only contain letters, numbers, dots, underscores, percent signs, plus signs, and hyphens before @psu.edu.ph.',
        ]);

        if (User::whereRaw('LOWER(name) = ?', [Str::lower($request->name)])->exists()) {
            throw ValidationException::withMessages([
                'name' => 'An account with this name already exists.',
            ]);
        }

        if (Student::whereRaw('UPPER(student_number) = ?', [$request->student_number])->exists()) {
            throw ValidationException::withMessages([
                'student_number' => 'An account with this student number already exists.',
            ]);
        }

        if (User::whereRaw('LOWER(email) = ?', [$request->email])->exists()) {
            throw ValidationException::withMessages([
                'email' => 'An account with this email already exists.',
            ]);
        }

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->student()->create([
                'student_number' => $request->student_number,
                'course' => $request->course,
                'year_level' => $request->year_level,
            ]);

            return $user;
        });

        event(new Registered($user));

        return redirect()
            ->route('register.confirmation')
            ->with('registered_email', $user->email);
    }
}
