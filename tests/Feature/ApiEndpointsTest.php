<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_list_sections_subjects_students_and_enrollments(): void
    {
        [$student, $subject, $enrollment] = $this->makeStudentSubjectAndEnrollment();

        $this->getJson('/api/sections')
            ->assertOk()
            ->assertJsonPath('0.name', $subject->section->name);

        $this->getJson('/api/subjects')
            ->assertOk()
            ->assertJsonPath('0.code', $subject->code)
            ->assertJsonPath('0.schedule_display', 'M/W 8:00 AM-9:30 AM LAB1');

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonPath('0.student_number', $student->student_number);

        $this->getJson('/api/enrollments')
            ->assertOk()
            ->assertJsonPath('0.id', $enrollment->id);
    }

    public function test_api_can_create_update_and_delete_enrollment(): void
    {
        [$student, $subject] = $this->makeStudentAndSubject();

        $createResponse = $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
        ]);

        $createResponse->assertCreated()
            ->assertJsonPath('status', 'Pending');

        $enrollmentId = $createResponse->json('id');

        $this->putJson("/api/enrollments/{$enrollmentId}", [
            'status' => 'Approved',
        ])
            ->assertOk()
            ->assertJsonPath('status', 'Approved');

        $this->deleteJson("/api/enrollments/{$enrollmentId}")
            ->assertOk()
            ->assertJson([
                'message' => 'Enrollment deleted successfully.',
            ]);

        $this->assertDatabaseMissing('enrollments', [
            'id' => $enrollmentId,
        ]);
    }

    public function test_api_rejects_invalid_enrollment_status(): void
    {
        [$student, $subject] = $this->makeStudentAndSubject();

        $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Done',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }

    /**
     * @return array{0: Student, 1: Subject, 2: Enrollment}
     */
    private function makeStudentSubjectAndEnrollment(): array
    {
        [$student, $subject] = $this->makeStudentAndSubject();

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);

        return [$student, $subject, $enrollment];
    }

    /**
     * @return array{0: Student, 1: Subject}
     */
    private function makeStudentAndSubject(): array
    {
        $user = User::factory()->create([
            'name' => 'API STUDENT',
            'email' => 'apistudent@psu.edu.ph',
            'password' => Hash::make('password'),
        ]);
        $user->forceFill(['role' => 'student'])->save();

        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-7777',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);

        $section = Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '2nd Semester',
            'school_year' => '2026-2027',
        ]);

        $subject = Subject::create([
            'section_id' => $section->id,
            'code' => 'IT 301',
            'title' => 'Web Systems',
            'units' => 3,
            'days' => 'M/W',
            'time_from' => '08:00',
            'time_to' => '09:30',
            'room' => 'LAB1',
            'schedule' => 'M/W 08:00-09:30 LAB1',
        ]);

        return [$student, $subject];
    }
}
