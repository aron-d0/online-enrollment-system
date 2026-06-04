<x-layout title="Manual Enrollment">

    <h1>Manual Enrollment</h1>

    <button type="button" onclick="window.location='{{ route('admin.dashboard') }}'">

        ← Back

    </button>

    <br><br>

    <form method="POST" action="{{ route('enrollments.admin.store') }}">

        @csrf

        <table>

            <tr>
                <th style="width:180px;">Field</th>
                <th>Value</th>
            </tr>

            <tr>
                <td>Student:</td>
                <td>

                    <select name="student_id" required style="width:100%; box-sizing:border-box;">

                        @foreach($students as $student)

                            <option value="{{ $student->id }}">

                                {{ $student->student_number }}
                                -
                                {{ $student->user->name }}

                            </option>

                        @endforeach

                    </select>

                </td>
            </tr>

            <tr>
                <td>Section:</td>
                <td>

                    <select id="sectionSelect" required style="width:100%; box-sizing:border-box;">

                        <option value="">
                            Select Section
                        </option>

                        @foreach($sections as $section)

                            <option value="{{ $section->id }}">
                                {{ $section->name }}
                            </option>

                        @endforeach

                    </select>

                </td>
            </tr>

        </table>

        <br>

        <table>

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

            @foreach(\App\Models\Subject::all() as $subject)

                <tr class="subject-row" data-section="{{ $subject->section_id }}" style="display:none;">

                    <td>

                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" checked>

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

        <div style="text-align:right;">

            <button type="submit">

                Enroll Student

            </button>

        </div>

    </form>

    <script>

        document
            .getElementById('sectionSelect')
            .addEventListener('change', function () {

                let sectionId = this.value;

                document
                    .querySelectorAll('.subject-row')
                    .forEach(row => {

                        row.style.display =
                            row.dataset.section === sectionId
                                ? ''
                                : 'none';

                    });

            });

    </script>

</x-layout>