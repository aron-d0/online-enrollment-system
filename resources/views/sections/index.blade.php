<x-layout title="Section Management">

    <h1>Section Management</h1>

    <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        ">

        <button type="button" onclick="history.back()">

            ← Back

        </button>

        <button type="button" onclick="window.location='{{ route('sections.create') }}'">

            Create Section

        </button>

    </div>

    @if($sections->count())

        <table>

            <tr>
                <th>Name</th>
                <th>Semester</th>
                <th>School Year</th>
                <th style="width:180px;">Actions</th>
            </tr>

            @foreach($sections as $section)

                <tr>

                    <td>
                        {{ $section->name }}
                    </td>

                    <td>
                        {{ $section->semester }}
                    </td>

                    <td>
                        {{ $section->school_year }}
                    </td>

                    <td>

                        <button type="button" onclick="window.location='{{ route('sections.edit', $section->id) }}'">

                            Edit

                        </button>

                        <form action="{{ route('sections.destroy', $section->id) }}" method="POST" style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Delete this section?')">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

            @endforeach

        </table>

    @else

        <p>
            No sections found.
        </p>

    @endif

</x-layout>