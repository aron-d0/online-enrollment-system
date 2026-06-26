<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::with([
            'subjects' => fn ($query) => $query
                ->withCount('enrollments')
                ->orderBy('code'),
        ])
            ->withCount('subjects')
            ->orderBy('school_year', 'desc')
            ->orderByRaw("CASE semester WHEN '1st Semester' THEN 1 WHEN '2nd Semester' THEN 2 WHEN 'Summer' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get();

        $subjectCount = $sections->sum('subjects_count');

        return view('subjects.index', compact('sections', 'subjectCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = $this->sectionsForForms();

        return view('subjects.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Subject::create($this->validatedSubjectData($request));

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        $sections = $this->sectionsForForms();

        return view(
            'subjects.edit',
            compact(
                'subject',
                'sections'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        $subject->update($this->validatedSubjectData($request, $subject));

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        if ($subject->enrollments()->exists()) {
            return back()->with(
                'error',
                'This subject already has student enrollments. Delete those enrollments first.'
            );
        }

        $subject->delete();

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'subject_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'subject_ids.*' => [
                'integer',
                'exists:subjects,id',
            ],
        ]);

        $subjects = Subject::withCount('enrollments')
            ->whereIn('id', $validated['subject_ids'])
            ->get();

        if ($subjects->contains(fn (Subject $subject) => $subject->enrollments_count > 0)) {
            return redirect()->route('subjects.index')->with(
                'error',
                'Some selected subjects already have enrollments. Delete those enrollments first.'
            );
        }

        Subject::whereIn('id', $subjects->pluck('id'))->delete();

        return redirect()->route('subjects.index')->with(
            'success',
            $subjects->count() . ' subject(s) deleted successfully.'
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = $this->normalizedCsvHeader(fgetcsv($file) ?: []);
        $imported = 0;
        $rowNumber = 1;

        $format = $this->csvImportFormat($header);

        if ($format === null) {
            fclose($file);

            throw ValidationException::withMessages([
                'csv_file' => 'CSV header must be: ' . implode(', ', $this->preferredCsvHeaders()),
            ]);
        }

        DB::transaction(function () use ($file, $header, $format, &$imported, &$rowNumber) {
            while (($row = fgetcsv($file)) !== false) {
                $rowNumber++;

                if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                    continue;
                }

                $csvRow = $this->combineCsvRow($header, $row);
                $data = $this->subjectDataFromCsvRow($csvRow, $format, $rowNumber);

                $validated = $this->validateSubjectArray($data, null, "Row {$rowNumber}: ");

                Subject::create($validated);
                $imported++;
            }
        });

        fclose($file);

        return redirect()
            ->route('subjects.index')
            ->with('success', "{$imported} subject(s) imported successfully.");
    }

    private function preferredCsvHeaders(): array
    {
        return [
            'section_name',
            'semester',
            'school_year',
            'code',
            'title',
            'units',
            'days',
            'time_from',
            'time_to',
            'room',
        ];
    }

    private function legacyCsvHeaders(): array
    {
        return [
            'section_id',
            'code',
            'title',
            'units',
            'schedule',
            'days',
            'time_from',
            'time_to',
            'room',
        ];
    }

    private function normalizedCsvHeader(array $header): array
    {
        return array_map(
            fn ($column) => Str::of((string) $column)->trim()->lower()->toString(),
            $header
        );
    }

    private function csvImportFormat(array $header): ?string
    {
        if ($header === $this->preferredCsvHeaders()) {
            return 'preferred';
        }

        if ($header === $this->legacyCsvHeaders()) {
            return 'legacy';
        }

        return null;
    }

    private function combineCsvRow(array $header, array $row): array
    {
        $row = array_pad($row, count($header), null);

        return array_combine($header, array_slice($row, 0, count($header)));
    }

    private function subjectDataFromCsvRow(array $row, string $format, int $rowNumber): array
    {
        if ($format === 'legacy') {
            return [
                'section_id' => $row['section_id'] ?? null,
                'code' => $row['code'] ?? null,
                'title' => $row['title'] ?? null,
                'units' => $row['units'] ?? null,
                'days' => $row['days'] ?? null,
                'time_from' => $row['time_from'] ?? null,
                'time_to' => $row['time_to'] ?? null,
                'room' => $row['room'] ?? null,
            ];
        }

        $sectionName = Str::upper(trim((string) ($row['section_name'] ?? '')));
        $semester = trim((string) ($row['semester'] ?? ''));
        $schoolYear = trim((string) ($row['school_year'] ?? ''));

        $section = Section::where('name', $sectionName)
            ->where('semester', $semester)
            ->where('school_year', $schoolYear)
            ->first();

        if (! $section) {
            throw ValidationException::withMessages([
                'csv_file' => "Row {$rowNumber}: Section {$sectionName} ({$semester}, {$schoolYear}) was not found.",
            ]);
        }

        return [
            'section_id' => $section->id,
            'code' => $row['code'] ?? null,
            'title' => $row['title'] ?? null,
            'units' => $row['units'] ?? null,
            'days' => $row['days'] ?? null,
            'time_from' => $row['time_from'] ?? null,
            'time_to' => $row['time_to'] ?? null,
            'room' => $row['room'] ?? null,
        ];
    }

    private function sectionsForForms()
    {
        return Section::orderBy('school_year', 'desc')
            ->orderByRaw("CASE semester WHEN '1st Semester' THEN 1 WHEN '2nd Semester' THEN 2 WHEN 'Summer' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get();
    }

    private function validatedSubjectData(Request $request, ?Subject $subject = null): array
    {
        return $this->validateSubjectArray($request->all(), $subject);
    }

    private function validateSubjectArray(array $data, ?Subject $subject = null, string $prefix = ''): array
    {
        $data = [
            ...$data,
            'code' => strtoupper(trim((string) ($data['code'] ?? ''))),
            'title' => trim((string) ($data['title'] ?? '')),
            'days' => strtoupper(trim((string) ($data['days'] ?? ''))),
            'room' => strtoupper(trim((string) ($data['room'] ?? ''))),
            'time_from' => $this->normalizeTimeInput($data['time_from'] ?? null),
            'time_to' => $this->normalizeTimeInput($data['time_to'] ?? null),
        ];

        $validator = Validator::make($data, [
            'section_id' => [
                'required',
                'integer',
                'exists:sections,id',
            ],
            'code' => [
                'required',
                'string',
                'max:30',
                'regex:/^[A-Z0-9 _-]+$/',
                Rule::unique('subjects', 'code')
                    ->where('section_id', $data['section_id'])
                    ->ignore($subject?->id),
            ],
            'title' => [
                'required',
                'string',
                'max:120',
            ],
            'units' => [
                'required',
                'integer',
                'min:1',
                'max:6',
            ],
            'days' => [
                'required',
                'string',
                'max:30',
            ],
            'time_from' => [
                'required',
                'date_format:H:i',
            ],
            'time_to' => [
                'required',
                'date_format:H:i',
                'after:time_from',
            ],
            'room' => [
                'required',
                'string',
                'max:50',
            ],
        ], [
            'code.regex' => $prefix . 'Subject code may only contain letters, numbers, spaces, underscores, and dashes.',
            'code.unique' => $prefix . 'That subject code already exists in the selected section.',
            'time_to.after' => $prefix . 'Time To must be later than Time From.',
        ]);

        $validated = $validator->validate();

        return [
            ...$validated,
            'schedule' => $this->formatSchedule($validated),
        ];
    }

    private function formatSchedule(array $subject): string
    {
        return "{$subject['days']} {$subject['time_from']}-{$subject['time_to']} {$subject['room']}";
    }

    private function normalizeTimeInput(?string $time): ?string
    {
        if ($time === null) {
            return null;
        }

        $time = trim($time);

        if ($time === '') {
            return null;
        }

        if (preg_match('/^(\d{1,2}):(\d{2})([ap])$/i', $time, $matches)) {
            $hour = (int) $matches[1];
            $minute = $matches[2];
            $period = strtolower($matches[3]);

            if ($period === 'p' && $hour !== 12) {
                $hour += 12;
            }

            if ($period === 'a' && $hour === 12) {
                $hour = 0;
            }

            return sprintf('%02d:%s', $hour, $minute);
        }

        if (preg_match('/^(\d{1,2}):(\d{2})(?::\d{2})?$/', $time, $matches)) {
            return sprintf('%02d:%s', (int) $matches[1], $matches[2]);
        }

        return $time;
    }
}
