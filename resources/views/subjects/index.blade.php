<x-layout title="Subject Management">
    <h1>Subjects</h1>

    @if(session('success'))

        <p>
            <strong>{{ session('success') }}</strong>
        </p>

    @endif

    <form method="POST" action="{{ route('subjects.import') }}" enctype="multipart/form-data">

        @csrf

        <button type="button" onclick="history.back()">

            ← Back

        </button>

        <br><br>

        <input type="file" name="csv_file" required>

        <button type="submit">
            Import CSV
        </button>

    </form>

    <div style="text-align:right; margin-bottom:20px;">

        <button type="button" onclick="window.location='{{ route('subjects.create') }}'">

            Create Subject

        </button>

    </div>

    <br>

    @if($subjects->count())

        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>Title</th>
                <th>Units</th>
                <th>Schedule</th>
                <th>Section</th>
                <th>Actions</th>
            </tr>

            @foreach($subjects as $subject)

                <tr>

                    <td>{{ $subject->id }}</td>
                    <td>{{ $subject->code }}</td>
                    <td>{{ $subject->title }}</td>
                    <td>{{ $subject->units }}</td>
                    <td>
                        ({{ $subject->days }}) {{ $subject->time_from }} - {{ $subject->time_to }}
                    </td>
                    <td>{{ $subject->section->name }}</td>

                    <td>

                        <button type="button" onclick="window.location='{{ route('subjects.edit', $subject->id) }}'">

                            Edit

                        </button>

                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </table>

    @else

        <p>No subjects found.</p>

    @endif
</x-layout>