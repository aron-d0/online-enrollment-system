<?php

namespace Tests\Feature;

use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminSubjectManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_subject_with_schedule_fields(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();

        $response = $this->actingAs($admin)
            ->post(route('subjects.store'), [
                'section_id' => $section->id,
                'code' => 'it 301',
                'title' => 'Web Systems',
                'units' => 3,
                'days' => 'mwf',
                'time_from' => '08:00',
                'time_to' => '09:30',
                'room' => 'itrm 3',
            ]);

        $response->assertRedirect(route('subjects.index'));

        $this->assertDatabaseHas('subjects', [
            'section_id' => $section->id,
            'code' => 'IT 301',
            'title' => 'Web Systems',
            'units' => 3,
            'days' => 'MWF',
            'time_from' => '08:00',
            'time_to' => '09:30',
            'room' => 'ITRM 3',
            'schedule' => 'MWF 08:00-09:30 ITRM 3',
        ]);
    }

    public function test_subjects_page_shows_sections_without_subjects(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();

        $response = $this->actingAs($admin)
            ->get(route('subjects.index'));

        $response->assertOk();
        $response->assertSee($section->name);
        $response->assertSee('0 Subjects');
        $response->assertSee('No subjects in this section yet.');
    }

    public function test_admin_cannot_create_duplicate_subject_code_in_same_section(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $this->makeSubject($section, ['code' => 'IT 301']);

        $response = $this->actingAs($admin)
            ->from(route('subjects.create'))
            ->post(route('subjects.store'), [
                'section_id' => $section->id,
                'code' => 'IT 301',
                'title' => 'Duplicate Web Systems',
                'units' => 3,
                'days' => 'MWF',
                'time_from' => '10:00',
                'time_to' => '11:30',
                'room' => 'ITRM 4',
            ]);

        $response->assertRedirect(route('subjects.create'));
        $response->assertSessionHasErrors('code');
    }

    public function test_admin_can_update_subject(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $subject = $this->makeSubject($section);

        $response = $this->actingAs($admin)
            ->put(route('subjects.update', $subject), [
                'section_id' => $section->id,
                'code' => 'IT 302',
                'title' => 'Information Management',
                'units' => 3,
                'days' => 'TTH',
                'time_from' => '13:00',
                'time_to' => '14:30',
                'room' => 'LAB 1',
            ]);

        $response->assertRedirect(route('subjects.index'));

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'code' => 'IT 302',
            'title' => 'Information Management',
            'days' => 'TTH',
            'schedule' => 'TTH 13:00-14:30 LAB 1',
        ]);
    }

    public function test_subject_edit_form_shows_existing_legacy_time_values(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $subject = $this->makeSubject($section, [
            'time_from' => '09:00a',
            'time_to' => '12:00p',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('subjects.edit', $subject));

        $response->assertOk();
        $response->assertSee('value="09:00"', false);
        $response->assertSee('value="12:00"', false);
    }

    public function test_subject_schedule_displays_in_twelve_hour_format(): void
    {
        $section = $this->makeSection();
        $subject = $this->makeSubject($section, [
            'days' => 'M/W',
            'time_from' => '13:00',
            'time_to' => '14:30',
            'room' => 'LAB1',
        ]);

        $this->assertSame('1:00 PM', $subject->timeFromForDisplay());
        $this->assertSame('2:30 PM', $subject->timeToForDisplay());
        $this->assertSame('M/W 1:00 PM-2:30 PM LAB1', $subject->scheduleForDisplay());
    }

    public function test_admin_can_delete_subject_without_enrollments(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $subject = $this->makeSubject($section);

        $response = $this->actingAs($admin)
            ->delete(route('subjects.destroy', $subject));

        $response->assertRedirect(route('subjects.index'));

        $this->assertDatabaseMissing('subjects', [
            'id' => $subject->id,
        ]);
    }

    public function test_admin_cannot_delete_subject_with_enrollments(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $subject = $this->makeSubject($section);
        $student = $this->makeStudent();

        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('subjects.index'))
            ->delete(route('subjects.destroy', $subject));

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
        ]);
    }

    public function test_admin_can_bulk_delete_subjects_without_enrollments(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $firstSubject = $this->makeSubject($section, ['code' => 'IT 301']);
        $secondSubject = $this->makeSubject($section, ['code' => 'IT 302']);

        $response = $this->actingAs($admin)
            ->delete(route('subjects.bulk-destroy'), [
                'subject_ids' => [
                    $firstSubject->id,
                    $secondSubject->id,
                ],
            ]);

        $response->assertRedirect(route('subjects.index'));

        $this->assertDatabaseMissing('subjects', [
            'id' => $firstSubject->id,
        ]);

        $this->assertDatabaseMissing('subjects', [
            'id' => $secondSubject->id,
        ]);
    }

    public function test_admin_cannot_bulk_delete_subjects_with_enrollments(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();
        $subject = $this->makeSubject($section);
        $student = $this->makeStudent();

        Enrollment::create([
            'student_id' => $student->id,
            'subject_id' => $subject->id,
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('subjects.index'))
            ->delete(route('subjects.bulk-destroy'), [
                'subject_ids' => [
                    $subject->id,
                ],
            ]);

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
        ]);
    }

    public function test_admin_can_import_subjects_from_csv(): void
    {
        $admin = $this->makeAdmin();
        $section = $this->makeSection();

        $file = UploadedFile::fake()->createWithContent(
            'subjects.csv',
            implode("\n", [
                'section_name,semester,school_year,code,title,units,days,time_from,time_to,room',
                "{$section->name},{$section->semester},{$section->school_year},IT 303,Capstone 1,3,SAT,09:00,12:00,ITRM 5",
            ])
        );

        $response = $this->actingAs($admin)
            ->post(route('subjects.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('subjects.index'));

        $this->assertDatabaseHas('subjects', [
            'section_id' => $section->id,
            'code' => 'IT 303',
            'title' => 'Capstone 1',
            'schedule' => 'SAT 09:00-12:00 ITRM 5',
        ]);
    }

    public function test_subject_csv_import_rejects_invalid_headers(): void
    {
        $admin = $this->makeAdmin();

        $file = UploadedFile::fake()->createWithContent(
            'subjects.csv',
            implode("\n", [
                'section,subject,units',
                'III-BSIT-A,Capstone 1,3',
            ])
        );

        $response = $this->actingAs($admin)
            ->from(route('subjects.index'))
            ->post(route('subjects.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHasErrors('csv_file');
    }

    public function test_student_cannot_import_subjects(): void
    {
        $student = $this->makeStudent();
        $file = UploadedFile::fake()->createWithContent(
            'subjects.csv',
            "section_id,code,title,units,schedule,days,time_from,time_to,room\n"
        );

        $response = $this->actingAs($student->user)
            ->post(route('subjects.import'), [
                'csv_file' => $file,
            ]);

        $response->assertRedirect('/portal');
    }

    private function makeAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => 'subjectadmin@psu.edu.ph',
        ]);

        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }

    private function makeSection(): Section
    {
        return Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);
    }

    private function makeSubject(Section $section, array $overrides = []): Subject
    {
        return Subject::create([
            'section_id' => $section->id,
            'code' => 'IT 301',
            'title' => 'Web Systems',
            'units' => 3,
            'days' => 'MWF',
            'time_from' => '08:00',
            'time_to' => '09:30',
            'room' => 'ITRM 3',
            'schedule' => 'MWF 08:00-09:30 ITRM 3',
            ...$overrides,
        ]);
    }

    private function makeStudent(): Student
    {
        $user = User::factory()->create([
            'email' => 'subjectstudent@psu.edu.ph',
        ]);

        $user->forceFill(['role' => 'student'])->save();

        return Student::create([
            'user_id' => $user->id,
            'student_number' => '22-LN-8888',
            'course' => 'BSIT',
            'year_level' => 3,
        ]);
    }
}
