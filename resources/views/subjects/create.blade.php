<x-layout title="Create Subject">

    <h1>Create Subject</h1>

    <button type="button" onclick="window.location='{{ route('subjects.index') }}'">

        ← Back

    </button>

    <br><br>

    <form action="{{ route('subjects.store') }}" method="POST">

        @csrf

        <table>

            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>

            <tr>
                <td style="width:180px;">Section:</td>
                <td>

                    <select name="section_id" required style="width:100%; box-sizing:border-box;">

                        @foreach($sections as $section)

                            <option value="{{ $section->id }}">
                                {{ $section->name }}
                            </option>

                        @endforeach

                    </select>

                </td>
            </tr>

            <tr>
                <td>Code:</td>
                <td>

                    <input type="text" name="code" placeholder="Ex. LN01_ELEC1" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Title:</td>
                <td>

                    <input type="text" name="title" placeholder="Ex. Web Systems and Technologies 2" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Units:</td>
                <td>

                    <input type="number" name="units" min="1" placeholder="Ex. 3" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Days:</td>
                <td>

                    <input type="text" name="days" placeholder="Ex. MWF" style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Time From:</td>
                <td>

                    <input type="time" name="time_from" style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Time To:</td>
                <td>

                    <input type="time" name="time_to" style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Room:</td>
                <td>

                    <input type="text" name="room" placeholder="Ex. ITRM 3" style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

        </table>

        <br>

        <div style="text-align:right;">

            <button type="submit">

                Save Subject

            </button>

        </div>

    </form>

</x-layout>