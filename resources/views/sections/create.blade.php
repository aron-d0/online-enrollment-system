<x-layout title="Create Section">

    <h1>Create Section</h1>

    <button type="button" onclick="window.location='{{ route('sections.index') }}'">

        ← Back

    </button>

    <br><br>

    <form action="{{ route('sections.store') }}" method="POST">

        @csrf

        <table>

            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>

            <tr>
                <td style="width:180px;">Section Name:</td>
                <td>

                    <input type="text" name="name" placeholder="Ex. III-BSIT-A" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Semester:</td>
                <td>

                    <input type="text" name="semester" placeholder="Ex. 1st Semester" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>School Year:</td>
                <td>

                    <input type="text" name="school_year" placeholder="Ex. 2025-2026" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

        </table>

        <br>

        <div style="text-align:right;">

            <button type="submit">

                Save Section

            </button>

        </div>

    </form>

</x-layout>