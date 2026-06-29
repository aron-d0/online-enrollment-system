<x-layout title="Edit Student">

    <div class="mb-8">

        <a href="{{ route('students.index') }}"
            class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-2 text-slate-300 transition hover:bg-white/10">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Students
        </a>

        <div class="mt-6 inline-flex items-center gap-2 rounded-full border border-blue-500/20 bg-blue-500/10 px-4 py-2 text-sm text-blue-300">
            <i class="fa-solid fa-user-pen"></i>
            Admin Student Editor
        </div>

        <h1 class="mt-4 text-5xl font-bold text-white">
            Edit Student
        </h1>

        <p class="mt-2 text-slate-400">
            Update the student record and linked login account together.
        </p>

    </div>

    <div class="rounded-[32px] border border-white/10 bg-white/5 p-8 shadow-[0_20px_60px_rgba(0,0,0,.35)] backdrop-blur-2xl">

        <form method="POST" action="{{ route('students.update', $student) }}" class="space-y-6">

            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300">
                        Full Name
                    </label>

                    <input id="name" name="name" type="text" required
                        value="{{ old('name', $student->user->name) }}"
                        oninput="this.value = this.value.toUpperCase()"
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="student_number" class="block text-sm font-medium text-slate-300">
                        Student Number
                    </label>

                    <input id="student_number" name="student_number" type="text" required
                        value="{{ old('student_number', $student->student_number) }}"
                        placeholder="Ex. 22-LN-1234"
                        pattern="[0-9]{2}-[A-Za-z]{2}-[0-9]{4}"
                        maxlength="10"
                        title="Use the specified format: 22-LN-1234."
                        data-student-number-format
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                    @error('student_number')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="course" class="block text-sm font-medium text-slate-300">
                        Course
                    </label>

                    <select id="course" name="course" required
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="BSIT" @selected(old('course', $student->course) === 'BSIT')>
                            BSIT
                        </option>
                    </select>

                    @error('course')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="year_level" class="block text-sm font-medium text-slate-300">
                        Year Level
                    </label>

                    <select id="year_level" name="year_level" required
                        class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="3" @selected((int) old('year_level', $student->year_level) === 3)>
                            III
                        </option>
                    </select>

                    @error('year_level')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="email" class="block text-sm font-medium text-slate-300">
                        Login Email
                    </label>

                    <div class="mt-2 flex rounded-2xl shadow-sm">
                        <input id="email" name="email_username" type="text" required
                            value="{{ old('email_username', str_replace('@psu.edu.ph', '', $student->user->email)) }}"
                            class="block w-full rounded-l-2xl rounded-r-none border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        <span
                            class="inline-flex items-center rounded-r-2xl border border-l-0 border-slate-700 bg-slate-800 px-4 text-sm text-slate-200">
                            @psu.edu.ph
                        </span>
                    </div>

                    @error('email_username')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="rounded-3xl border border-white/10 bg-slate-950/30 p-6">

                <h2 class="text-xl font-bold text-white">
                    Password Reset
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Leave these blank to keep the current login password.
                </p>

                <div class="mt-5 grid grid-cols-1 gap-6 lg:grid-cols-2">

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300">
                            New Password
                        </label>

                        <input id="password" name="password" type="password" autocomplete="new-password"
                            placeholder="At least 8 characters"
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">

                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300">
                            Confirm New Password
                        </label>

                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password"
                            class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-900/60 px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                </div>

            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:justify-end">

                <a href="{{ route('students.index') }}"
                    class="rounded-2xl border border-white/10 bg-white/5 px-6 py-3 text-center font-semibold text-slate-300 transition hover:bg-white/10">
                    Cancel
                </a>

                <button type="submit"
                    class="rounded-2xl border border-blue-500/20 bg-blue-500/10 px-6 py-3 font-semibold text-blue-300 shadow-lg shadow-blue-950/20 backdrop-blur-xl transition hover:bg-blue-500/20 hover:text-blue-200">
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</x-layout>
