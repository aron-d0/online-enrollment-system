<x-layout title="Student Management">
    <h1>Student Management</h1>

    <button onclick="history.back()">
        ← Back
    </button>

    <br><br>

    <table border="1" cellpadding="8">

        <tr>
            <th>Student Number</th>
            <th>Name</th>
            <th>Course</th>
            <th>Year Level</th>
            <th>Email</th>
        </tr>

        @foreach($students as $student)

            <tr>

                <td>
                    {{ $student->student_number }}
                </td>

                <td>
                    {{ $student->user->name }}
                </td>

                <td>
                    {{ $student->course }}
                </td>

                <td>
                    {{ $student->year_level }}
                </td>

                <td>
                    {{ $student->user->email }}
                </td>

            </tr>

        @endforeach

    </table>
</x-layout>