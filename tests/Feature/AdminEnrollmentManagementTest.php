<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEnrollmentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_enrollments_page(): void
    {
        [$admin, $enrollment] = $this->makeAdminAndEnrollment();

        $response = $this->actingAs($admin)->get(route('enrollments.index'));

        $response->assertOk();
        $response->assertSee('Enrollments');
        $response->assertSee($enrollment->student->user->name);
        $response->assertSee($enrollment->subject->code);
    }

    public function test_admin_can_approve_enrollment_without_page_reload_response(): void
    {
        [$admin, $enrollment] = $this->makeAdminAndEnrollment();

        $response = $this->actingAs($admin)
            ->patchJson(route('enrollments.approve', $enrollment));

        $response->assertOk()
            ->assertJson([
                'status' => 'Approved',
                'message' => 'Enrollment approved successfully.',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'Approved',
        ]);
    }

    public function test_admin_can_reject_enrollment_without_page_reload_response(): void
    {
        [$admin, $enrollment] = $this->makeAdminAndEnrollment();

        $response = $this->actingAs($admin)
            ->patchJson(route('enrollments.reject', $enrollment));

        $response->assertOk()
            ->assertJson([
                'status' => 'Rejected',
                'message' => 'Enrollment rejected successfully.',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'Rejected',
        ]);
    }

    public function test_admin_can_bulk_approve_selected_enrollments(): void
    {
        [$admin, $firstEnrollment] = $this->makeAdminAndEnrollment();
        $secondEnrollment = $this->makeEnrollmentForSameStudent($firstEnrollment);

        $response = $this->actingAs($admin)
            ->patchJson(route('enrollments.bulk-status'), [
                'enrollment_ids' => [
                    $firstEnrollment->id,
                    $secondEnrollment->id,
                ],
                'status' => 'Approved',
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'Approved',
                'message' => 'Selected enrollments approved successfully.',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $firstEnrollment->id,
            'status' => 'Approved',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $secondEnrollment->id,
            'status' => 'Approved',
        ]);
    }

    public function test_admin_can_bulk_reject_selected_enrollments(): void
    {
        [$admin, $firstEnrollment] = $this->makeAdminAndEnrollment();
        $secondEnrollment = $this->makeEnrollmentForSameStudent($firstEnrollment);

        $response = $this->actingAs($admin)
            ->patchJson(route('enrollments.bulk-status'), [
                'enrollment_ids' => [
                    $firstEnrollment->id,
                    $secondEnrollment->id,
                ],
                'status' => 'Rejected',
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'Rejected',
                'message' => 'Selected enrollments rejected successfully.',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $firstEnrollment->id,
            'status' => 'Rejected',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $secondEnrollment->id,
            'status' => 'Rejected',
        ]);
    }

    public function test_admin_can_bulk_delete_selected_enrollments(): void
    {
        [$admin, $firstEnrollment] = $this->makeAdminAndEnrollment();
        $secondEnrollment = $this->makeEnrollmentForSameStudent($firstEnrollment);

        $response = $this->actingAs($admin)
            ->deleteJson(route('enrollments.bulk-destroy'), [
                'enrollment_ids' => [
                    $firstEnrollment->id,
                    $secondEnrollment->id,
                ],
            ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Selected enrollments deleted successfully.',
            ]);

        $this->assertDatabaseMissing('enrollments', [
            'id' => $firstEnrollment->id,
        ]);

        $this->assertDatabaseMissing('enrollments', [
            'id' => $secondEnrollment->id,
        ]);
    }

    /**
     * @return array{0: User, 1: Enrollment}
     */
    private function makeAdminAndEnrollment(): array
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@psu.edu.ph',
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        $user = User::factory()->create([
            'name' => 'ENROLLMENT STUDENT',
            'email' => 'enrollmentstudent@psu.edu.ph',
        ]);
        $user->forceFill(['role' => 'student'])->save();

        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-5555',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);

        $section = Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $subject = Subject::create([
            'section_id' => $section->id,
            'code' => 'IT 301',
            'title' => 'Web Systems',
            'units' => 3,
            'schedule' => 'Mon 08:00-10:00',
        ]);

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);

        return [$admin, $enrollment->load('student.user', 'subject')];
    }

    private function makeEnrollmentForSameStudent(Enrollment $enrollment): Enrollment
    {
        $subject = Subject::create([
            'section_id' => $enrollment->subject->section_id,
            'code' => 'IT 302',
            'title' => 'Information Management',
            'units' => 3,
            'schedule' => 'Tue 08:00-10:00',
        ]);

        return Enrollment::create([
            'student_id' => $enrollment->student_id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);
    }
}
