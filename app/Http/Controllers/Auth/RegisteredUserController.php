<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $emailUsername = Str::lower((string) $request->input('email_username'));

        $request->merge([
            'name' => Str::upper((string) $request->input('name')),
            'email_username' => $emailUsername,
            'email' => $emailUsername.'@psu.edu.ph',
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => ['required', 'string', 'max:255', 'unique:'.Student::class],
            'course' => ['required', 'string', Rule::in(['BSCS', 'BSIT', 'BSMath'])],
            'year_level' => ['required', 'integer', 'min:1', 'max:4'],
            'email_username' => ['required', 'string', 'max:64', 'regex:/^[a-z0-9._%+-]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (User::whereRaw('LOWER(name) = ?', [Str::lower($request->name)])->exists()) {
            throw ValidationException::withMessages([
                'name' => 'An account with this name already exists.',
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

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
