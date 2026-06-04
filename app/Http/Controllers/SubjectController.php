<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::with('section')->get();

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();

        return view('subjects.create', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Subject::create([
            'section_id' => $request->section_id,
            'code' => $request->code,
            'title' => $request->title,
            'units' => $request->units,
            'days' => $request->days,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'room' => $request->room,
        ]);

        return redirect()->route('subjects.index');
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
        $sections = \App\Models\Section::all();

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
        $subject->update([
            'section_id' => $request->section_id,
            'code' => $request->code,
            'title' => $request->title,
            'units' => $request->units,
            'days' => $request->days,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'room' => $request->room,
        ]);

        return redirect()->route('subjects.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index');
    }
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = fopen(
            $request->file('csv_file')->getRealPath(),
            'r'
        );

        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {

            Subject::create([
                'section_id' => $row[0],
                'code' => $row[1],
                'title' => $row[2],
                'units' => $row[3],
                'schedule' => $row[4],
                'days' => $row[5],
                'time_from' => $row[6],
                'time_to' => $row[7],
                'room' => $row[8],
            ]);
        }

        fclose($file);

        return redirect()
            ->route('subjects.index')
            ->with(
                'success',
                'Subjects imported successfully.'
            );
    }
}
