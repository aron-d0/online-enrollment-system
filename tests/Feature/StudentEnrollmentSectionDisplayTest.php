<?php

namespace Tests\Feature;

use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEnrollmentSectionDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_enrollment_records_show_the_finalized_section(): void
    {
        [$user, $student, $section, $subject] = $this->makeStudentSectionAndSubject();

        $response = $this->actingAs($user)
            ->post(route('enroll.store'), [
                'section_id' => $section->id,
                'subjects' => [
                    $subject->id,
                ],
            ]);

        $response->assertRedirect(route('portal', ['section_id' => $section->id]));

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);

        $this->actingAs($user)
            ->get(route('portal'))
            ->assertOk()
            ->assertSee('Enrolled Section')
            ->assertSee($section->name)
            ->assertSee($section->semester)
            ->assertSee($section->school_year);
    }

    public function test_student_cannot_finalize_subjects_from_a_different_section(): void
    {
        [$user, $student, $section] = $this->makeStudentSectionAndSubject();

        $otherSection = Section::create([
            'name' => 'III-BSIT-B',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $otherSubject = Subject::create([
            'section_id' => $otherSection->id,
            'code' => 'IT 302',
            'title' => 'Information Management',
            'units' => 3,
            'schedule' => 'T 08:00-10:00 ITRM2',
            'days' => 'T',
            'time_from' => '08:00',
            'time_to' => '10:00',
            'room' => 'ITRM2',
        ]);

        $this->actingAs($user)
            ->from(route('portal', ['section_id' => $section->id]))
            ->post(route('enroll.store'), [
                'section_id' => $section->id,
                'subjects' => [
                    $otherSubject->id,
                ],
            ])
            ->assertSessionHasErrors('subjects');

        $this->assertDatabaseMissing('enrollments', [
            'student_id' => $student->id,
            'subject_id' => $otherSubject->id,
        ]);
    }

    public function test_student_portal_prompts_for_section_before_finalizing(): void
    {
        [$user] = $this->makeStudentSectionAndSubject();

        $this->actingAs($user)
            ->get(route('portal'))
            ->assertOk()
            ->assertSee('No section loaded yet. Select a section first, then load its subjects.')
            ->assertSee('No Section Loaded Yet');
    }

    /**
     * @return array{0: User, 1: Student, 2: Section, 3: Subject}
     */
    private function makeStudentSectionAndSubject(): array
    {
        $user = User::factory()->create([
            'name' => 'SECTION TEST STUDENT',
            'email' => 'sectionstudent@psu.edu.ph',
        ]);
        $user->forceFill(['role' => 'student'])->save();

        $student = Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-1212',
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
            'schedule' => 'M 08:00-10:00 ITRM1',
            'days' => 'M',
            'time_from' => '08:00',
            'time_to' => '10:00',
            'room' => 'ITRM1',
        ]);

        return [$user, $student, $section, $subject];
    }
}
