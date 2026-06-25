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

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'student_number' => '22-LN-9999',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

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
