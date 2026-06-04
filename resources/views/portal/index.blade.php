<x-layout title="Student Portal">

    @if(session('success'))
        <p>
            <strong>{{ session('success') }}</strong>
        </p>
    @endif

    <h3>Student Information</h3>

    @if($isEnrolled)

        <p>
            <strong>Status:</strong>
            ENROLLED
        </p>

    @else

        <p>
            <strong>Status:</strong>
            NOT ENROLLED
        </p>

    @endif

    <p>
        <strong>Student Number:</strong>
        {{ $student->student_number }}
    </p>

    <p>
        <strong>Name:</strong>
        {{ auth()->user()->name }}
    </p>

    <p>
        <strong>Course:</strong>
        {{ $student->course }}
    </p>

    <p>
        <strong>Year Level:</strong>
        {{ $student->year_level }}
    </p>

    <br><hr>

    <h3>Current Enrolled Subjects</h3>

    <table border="1" cellpadding="8">

        <tr>
            <th>CODE</th>
            <th>SUBJECT</th>
            <th>UNITS</th>
            <th>FROM</th>
            <th>TO</th>
            <th>DAYS</th>
            <th>ROOM</th>
            <th>STATUS</th>
        </tr>

        @foreach($enrollments as $enrollment)

            <tr>

                <td>{{ $enrollment->subject->code }}</td>
                <td>{{ $enrollment->subject->title }}</td>
                <td>{{ $enrollment->subject->units }}</td>
                <td>{{ $enrollment->subject->time_from }}</td>
                <td>{{ $enrollment->subject->time_to }}</td>
                <td>{{ $enrollment->subject->days }}</td>
                <td>{{ $enrollment->subject->room }}</td>
                <td>{{ $enrollment->status }}</td>

            </tr>

        @endforeach

    </table>

    <br>
    <hr>

    <div style="text-align:center;">

        <form method="GET" action="{{ route('portal') }}">

            <label>Select Section</label>

            <select name="section_id">

                <option value="">
                    -- Select Section --
                </option>

                @foreach($sections as $section)

                    <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                        {{ $section->name }}
                    </option>

                @endforeach

            </select>

            <button type="submit">
                Load Subjects
            </button>

        </form>

    </div>

    <br>

    <form method="POST" action="{{ route('enroll.store') }}">

        @csrf

        <input type="hidden" name="section_id" value="{{ request('section_id') }}">

        <table border="1" cellpadding="8">

            <tr>
                <th>STAT</th>
                <th>CODE</th>
                <th>SUBJECT</th>
                <th>UNITS</th>
                <th>FROM</th>
                <th>TO</th>
                <th>DAYS</th>
                <th>ROOM</th>
            </tr>

            @foreach($subjects as $subject)

                <tr>

                    <td>
                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" checked {{ $isEnrolled ? 'disabled' : '' }}>
                    </td>

                    <td>
                        {{ $subject->code }}
                    </td>

                    <td>
                        {{ $subject->title }}
                    </td>

                    <td>
                        {{ $subject->units }}
                    </td>

                    <td>
                        {{ $subject->time_from }}
                    </td>

                    <td>
                        {{ $subject->time_to }}
                    </td>

                    <td>
                        {{ $subject->days }}
                    </td>

                    <td>
                        {{ $subject->room }}
                    </td>

                </tr>

            @endforeach

        </table>

        <br>

        <div style="text-align:right; margin-top:20px;">

            @if($isEnrolled)

                <button disabled>
                    FINALIZED
                </button>

            @else

                <button type="submit" onclick="return confirm('Are you sure you want to finalize enrollment?')">

                    Finalize

                </button>

            @endif

        </div>

    </form>
</x-layout>