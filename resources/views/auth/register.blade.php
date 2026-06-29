<x-guest-layout>

    <div class="text-center mb-6">

        <h2 class="text-2xl font-bold text-white">
            Create Account
        </h2>

        <p class="mt-2 text-slate-400">
            Register as a student to access the enrollment portal
        </p>

    </div>

    <form method="POST" action="{{ route('register') }}">

        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="text-slate-300" />

            <x-text-input id="name" class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                pattern="[A-Za-z .'\-]+"
                title="Use letters, spaces, periods, hyphens, and apostrophes only."
                placeholder="Ex. JUAN DELA CRUZ" oninput="this.value = this.value.toUpperCase()" />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="student_number" :value="__('Student Number')" class="text-slate-300" />

            <x-text-input id="student_number"
                class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="text" name="student_number" :value="old('student_number')" required autocomplete="off"
                pattern="[0-9]{2}-[A-Za-z]{2}-[0-9]{4}"
                maxlength="10"
                title="Use the specified format: 22-LN-1234."
                placeholder="Ex. 22-LN-1234" oninput="this.value = this.value.toUpperCase()" />

            <x-input-error :messages="$errors->get('student_number')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-5">

            <div>
                <x-input-label for="course" :value="__('Course')" class="text-slate-300" />

                <select id="course" name="course" required
                    class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled @selected(old('course') === null)>Select course</option>

                    <option value="BSIT" @selected(old('course') === 'BSIT')>
                        BSIT
                    </option>
                </select>

                <x-input-error :messages="$errors->get('course')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="year_level" :value="__('Year Level')" class="text-slate-300" />

                <select id="year_level" name="year_level" required
                    class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="" disabled @selected(old('year_level') === null)>Select year</option>

                    <option value="3" @selected((int) old('year_level') === 3)>
                        III
                    </option>
                </select>

                <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
            </div>

        </div>

        <br>

        <div class="mt-5">
            <x-input-label for="email" :value="__('Email')" class="text-slate-300" />

            <div class="mt-2 flex rounded-lg shadow-sm">
                <x-text-input id="email"
                    class="block w-full rounded-r-none bg-slate-800 border-slate-700 text-white"
                    type="text" name="email_username" :value="old('email_username')" required autocomplete="username"
                    maxlength="64"
                    pattern="[a-zA-Z0-9._%+\-]+"
                    title="Enter only the part before @psu.edu.ph. Do not include @psu.edu.ph."
                    oninput="this.value = this.value.toLowerCase().replace('@psu.edu.ph', '').replace('@', '')" />

                <span
                    class="inline-flex items-center rounded-r-lg border border-l-0 border-slate-700 bg-slate-700 px-4 text-sm text-slate-200">
                    @psu.edu.ph
                </span>
            </div>

            <x-input-error :messages="$errors->get('email_username')" class="mt-2" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" :value="__('Password')" class="text-slate-300" />

            <x-text-input id="password" class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg"
                type="password" name="password" required autocomplete="new-password"
                minlength="8"
                placeholder="At least 8 characters" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-5">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-300" />

            <x-text-input id="password_confirmation"
                class="block mt-2 w-full bg-slate-800 border-slate-700 text-white rounded-lg" type="password"
                name="password_confirmation" required autocomplete="new-password"
                minlength="8" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-6">

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-3 rounded-lg shadow-lg">
                Register
            </button>

        </div>

    </form>

    <div class="mt-6 pt-6 border-t border-slate-700 text-center">

        <p class="text-sm text-slate-400">
            Already have an account?
        </p>

        <a href="{{ route('login') }}"
            class="mt-3 block w-full border border-blue-500/70 text-blue-300 hover:bg-blue-500/10 hover:text-blue-200 transition font-semibold py-3 rounded-lg">
            Back to Login
        </a>

    </div>

</x-guest-layout>
