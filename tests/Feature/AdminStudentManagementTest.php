<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_student_edit_screen(): void
    {
        [$admin, $student] = $this->makeAdminAndStudent();

        $response = $this->actingAs($admin)->get(route('students.edit', $student));

        $response->assertOk();
        $response->assertSee('Edit Student');
        $response->assertSee($student->student_number);
    }

    public function test_admin_can_view_student_create_screen(): void
    {
        [$admin] = $this->makeAdminAndStudent();

        $response = $this->actingAs($admin)->get(route('students.create'));

        $response->assertOk();
        $response->assertSee('Add Student');
    }

    public function test_admin_can_create_linked_user_and_student_records(): void
    {
        [$admin] = $this->makeAdminAndStudent();

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'name' => 'New Student',
            'student_number' => '22-ln-1234',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'newstudent',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'NEW STUDENT',
            'email' => 'newstudent@psu.edu.ph',
            'role' => 'student',
        ]);

        $user = User::where('email', 'newstudent@psu.edu.ph')->firstOrFail();

        $this->assertTrue(Hash::check('password123', $user->password));

        $this->assertDatabaseHas('students', [
            'user_id' => $user->id,
            'student_number' => '22-LN-1234',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);
    }

    public function test_admin_can_update_linked_user_and_student_records(): void
    {
        [$admin, $student] = $this->makeAdminAndStudent();

        $response = $this->actingAs($admin)->patch(route('students.update', $student), [
            'name' => 'Updated Student',
            'student_number' => '22-ln-9999',
            'course' => 'BSIT',
            'year_level' => 3,
            'email_username' => 'updatedstudent',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('students.index'));

        $student->refresh();
        $student->user->refresh();

        $this->assertSame('UPDATED STUDENT', $student->user->name);
        $this->assertSame('updatedstudent@psu.edu.ph', $student->user->email);
        $this->assertTrue(Hash::check('newpassword', $student->user->password));
        $this->assertSame('22-LN-9999', $student->student_number);
        $this->assertSame('BSIT', $student->course);
        $this->assertSame(3, $student->year_level);
    }

    public function test_admin_can_delete_student_and_linked_login_account(): void
    {
        [$admin, $student] = $this->makeAdminAndStudent();
        $userId = $student->user_id;
        $studentId = $student->id;

        $response = $this->actingAs($admin)->delete(route('students.destroy', $student));

        $response->assertRedirect(route('students.index'));

        $this->assertDatabaseMissing('users', ['id' => $userId]);
        $this->assertDatabaseMissing('students', ['id' => $studentId]);
    }

    public function test_non_admin_cannot_manage_students(): void
    {
        [, $student] = $this->makeAdminAndStudent();
        $regularUser = User::factory()->create();
        $regularUser->forceFill(['role' => 'student'])->save();

        $this->actingAs($regularUser)
            ->get(route('students.create'))
            ->assertRedirect('/portal');

        $this->actingAs($regularUser)
            ->post(route('students.store'), [])
            ->assertRedirect('/portal');

        $this->actingAs($regularUser)
            ->get(route('students.edit', $student))
            ->assertRedirect('/portal');

        $this->actingAs($regularUser)
            ->patch(route('students.update', $student), [])
            ->assertRedirect('/portal');

        $this->actingAs($regularUser)
            ->delete(route('students.destroy', $student))
            ->assertRedirect('/portal');
    }

    /**
     * @return array{0: User, 1: Student}
     */
    private function makeAdminAndStudent(): array
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@psu.edu.ph',
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        $user = User::factory()->create([
            'name' => 'ORIGINAL STUDENT',
            'email' => 'original@psu.edu.ph',
            'password' => Hash::make('password'),
        ]);
        $user->forceFill(['role' => 'student'])->save();

        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-0001',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);

        return [$admin, $student];
    }
}
