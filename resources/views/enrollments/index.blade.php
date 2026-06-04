<x-layout title="Enrollment Reports">

    <h1>Enrollment Reports</h1>

    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
    ">

        <button type="button" onclick="window.location='{{ route('admin.dashboard') }}'">

            ← Back

        </button>

        <div>

            <form method="GET" action="{{ route('enrollments.export.json') }}" style="display:inline;">

                <button type="submit">

                    Export JSON

                </button>

            </form>

            <form method="GET" action="{{ route('enrollments.export.csv') }}" style="display:inline;">

                <button type="submit">

                    Export CSV

                </button>

            </form>

        </div>

    </div>

    @if(session('success'))

        <p>
            <strong>{{ session('success') }}</strong>
        </p>

    @endif

    <table>

        <tr>

            <th>Student Number</th>
            <th>Student Name</th>
            <th>Subject Code</th>
            <th>Subject</th>
            <th>Units</th>
            <th>Status</th>
            <th>Action</th>

        </tr>

        @foreach($enrollments as $enrollment)

            <tr>

                <td>
                    {{ $enrollment->student->student_number }}
                </td>

                <td>
                    {{ $enrollment->student->user->name }}
                </td>

                <td>
                    {{ $enrollment->subject->code }}
                </td>

                <td>
                    {{ $enrollment->subject->title }}
                </td>

                <td>
                    {{ $enrollment->subject->units }}
                </td>

                <td>
                    {{ $enrollment->status }}
                </td>

                <td style="white-space:nowrap;">

                    <form method="POST" action="{{ route('enrollments.approve', $enrollment->id) }}"
                        style="display:inline;">

                        @csrf
                        @method('PATCH')

                        <button type="submit" style="background:green;">

                            Approve

                        </button>

                    </form>

                    <form method="POST" action="{{ route('enrollments.reject', $enrollment->id) }}" style="display:inline;">

                        @csrf
                        @method('PATCH')

                        <button type="submit" style="background:red;">

                            Reject

                        </button>

                    </form>

                    <form method="POST" action="{{ route('enrollments.reset', $enrollment->student->id) }}"
                        style="display:inline;">

                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Reset all enrollments for this student?')">

                            Reset

                        </button>

                    </form>

                </td>

            </tr>

        @endforeach

    </table>

</x-layout>