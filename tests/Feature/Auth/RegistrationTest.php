<?php

namespace Tests\Feature\Auth;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_confirmation_screen_can_be_rendered(): void
    {
        $response = $this->get('/register/confirmation');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'student_number' => '22-ln-9999',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('register.confirmation', absolute: false));
        $response->assertSessionHas('registered_email', 'test@psu.edu.ph');

        $this->assertDatabaseHas('users', [
            'name' => 'TEST USER',
            'email' => 'test@psu.edu.ph',
            'role' => 'student',
        ]);

        $this->assertDatabaseHas('students', [
            'student_number' => '22-LN-9999',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);
    }

    public function test_registration_requires_complete_non_blank_fields(): void
    {
        $this->post('/register', [
            'name' => '   ',
            'student_number' => '   ',
            'course' => '',
            'year_level' => '',
            'email_username' => '   ',
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors([
            'name',
            'student_number',
            'course',
            'year_level',
            'email_username',
            'password',
        ]);
    }

    public function test_registration_only_accepts_bsit_third_year_students(): void
    {
        $this->post('/register', [
            'name' => 'Course Mismatch',
            'student_number' => '22-LN-8888',
            'course' => 'BSCS',
            'year_level' => 3,
            'email_username' => 'coursemismatch',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('course');

        $this->post('/register', [
            'name' => 'Year Mismatch',
            'student_number' => '22-LN-8887',
            'course' => 'BSIT',
            'year_level' => 4,
            'email_username' => 'yearmismatch',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('year_level');
    }

    public function test_existing_student_records_cannot_register_again(): void
    {
        $user = User::create([
            'name' => 'Existing Student',
            'email' => 'existing@psu.edu.ph',
            'password' => Hash::make('password'),
        ]);

        Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-0001',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);

        $this->post('/register', [
            'name' => 'Existing Student',
            'student_number' => '22-LN-9998',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'newstudent',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('name');

        $this->post('/register', [
            'name' => 'NEW STUDENT',
            'student_number' => '22-LN-0001',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'newstudent',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('student_number');

        $this->post('/register', [
            'name' => 'NEW STUDENT',
            'student_number' => '22-LN-9998',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'existing',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('email');
    }
}
