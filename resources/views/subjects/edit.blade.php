<x-layout title="Edit Subject">

    <h1>Edit Subject</h1>

    <button type="button" onclick="window.location='{{ route('subjects.index') }}'">

        ← Back

    </button>

    <br><br>

    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">

        @csrf
        @method('PUT')

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

                            <option value="{{ $section->id }}" {{ $subject->section_id == $section->id ? 'selected' : '' }}>

                                {{ $section->name }}

                            </option>

                        @endforeach

                    </select>

                </td>
            </tr>

            <tr>
                <td>Code:</td>
                <td>

                    <input type="text" name="code" value="{{ $subject->code }}" placeholder="Ex. BSIT101" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Title:</td>
                <td>

                    <input type="text" name="title" value="{{ $subject->title }}"
                        placeholder="Ex. Web Systems and Technologies 2" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Units:</td>
                <td>

                    <input type="number" name="units" value="{{ $subject->units }}" min="1" placeholder="Ex. 3" required
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Days:</td>
                <td>

                    <input type="text" name="days" value="{{ $subject->days }}" placeholder="Ex. MWF"
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Time From:</td>
                <td>

                    <input type="time" name="time_from" value="{{ $subject->time_from }}"
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Time To:</td>
                <td>

                    <input type="time" name="time_to" value="{{ $subject->time_to }}"
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

            <tr>
                <td>Room:</td>
                <td>

                    <input type="text" name="room" value="{{ $subject->room }}" placeholder="Ex. ITRM 3"
                        style="width:100%; box-sizing:border-box;">

                </td>
            </tr>

        </table>

        <br>

        <div style="text-align:right;">

            <button type="submit">

                Update Subject

            </button>

        </div>

    </form>

</x-layout>