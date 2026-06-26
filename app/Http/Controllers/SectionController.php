<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::withCount('subjects')
            ->orderBy('school_year', 'desc')
            ->orderByRaw("CASE semester WHEN '1st Semester' THEN 1 WHEN '2nd Semester' THEN 2 WHEN 'Summer' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get();

        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Section::create($this->validatedSectionData($request));

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        return view('sections.edit', compact('section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Section $section)
    {
        $section->update($this->validatedSectionData($request, $section));

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        if ($section->subjects()->exists()) {
            return back()->with(
                'error',
                'This section still has subjects assigned. Move or delete those subjects first.'
            );
        }

        $section->delete();

        return redirect()
            ->route('sections.index')
            ->with('success', 'Section deleted successfully.');
    }

    private function validatedSectionData(Request $request, ?Section $section = null): array
    {
        $request->merge([
            'name' => strtoupper(trim((string) $request->name)),
            'semester' => trim((string) $request->semester),
            'school_year' => trim((string) $request->school_year),
        ]);

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[A-Z0-9 -]+$/',
                Rule::unique('sections', 'name')
                    ->where('semester', $request->semester)
                    ->where('school_year', $request->school_year)
                    ->ignore($section?->id),
            ],
            'semester' => [
                'required',
                Rule::in([
                    '1st Semester',
                    '2nd Semester',
                    'Summer',
                ]),
            ],
            'school_year' => [
                'required',
                'regex:/^\d{4}-\d{4}$/',
            ],
        ], [
            'name.regex' => 'Section name may only contain letters, numbers, spaces, and dashes.',
            'name.unique' => 'That section already exists for the selected semester and school year.',
            'school_year.regex' => 'School year must use the format YYYY-YYYY, for example 2026-2027.',
        ]);
    }
}
