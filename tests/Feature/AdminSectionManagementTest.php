<?php

namespace Tests\Feature;

use App\Models\Section;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSectionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_section_with_valid_academic_fields(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->post(route('sections.store'), [
                'name' => 'iii-bsit-a',
                'semester' => '1st Semester',
                'school_year' => '2026-2027',
            ]);

        $response->assertRedirect(route('sections.index'));

        $this->assertDatabaseHas('sections', [
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);
    }

    public function test_admin_cannot_create_duplicate_section_for_same_term(): void
    {
        $admin = $this->makeAdmin();

        Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('sections.create'))
            ->post(route('sections.store'), [
                'name' => 'III-BSIT-A',
                'semester' => '1st Semester',
                'school_year' => '2026-2027',
            ]);

        $response->assertRedirect(route('sections.create'));
        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_update_section(): void
    {
        $admin = $this->makeAdmin();
        $section = Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('sections.update', $section), [
                'name' => 'III-BSIT-B',
                'semester' => '2nd Semester',
                'school_year' => '2026-2027',
            ]);

        $response->assertRedirect(route('sections.index'));

        $this->assertDatabaseHas('sections', [
            'id' => $section->id,
            'name' => 'III-BSIT-B',
            'semester' => '2nd Semester',
            'school_year' => '2026-2027',
        ]);
    }

    public function test_admin_can_delete_section_without_subjects(): void
    {
        $admin = $this->makeAdmin();
        $section = Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('sections.destroy', $section));

        $response->assertRedirect(route('sections.index'));

        $this->assertDatabaseMissing('sections', [
            'id' => $section->id,
        ]);
    }

    public function test_admin_cannot_delete_section_that_still_has_subjects(): void
    {
        $admin = $this->makeAdmin();
        $section = Section::create([
            'name' => 'III-BSIT-A',
            'semester' => '1st Semester',
            'school_year' => '2026-2027',
        ]);

        Subject::create([
            'section_id' => $section->id,
            'code' => 'IT 301',
            'title' => 'Web Systems',
            'units' => 3,
            'schedule' => 'MWF 08:00-09:00',
        ]);

        $response = $this->actingAs($admin)
            ->from(route('sections.index'))
            ->delete(route('sections.destroy', $section));

        $response->assertRedirect(route('sections.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('sections', [
            'id' => $section->id,
        ]);
    }

    private function makeAdmin(): User
    {
        $admin = User::factory()->create([
            'email' => 'sectionadmin@psu.edu.ph',
        ]);

        $admin->forceFill(['role' => 'admin'])->save();

        return $admin;
    }
}
