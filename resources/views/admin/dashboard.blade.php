<x-layout title="Admin Dashboard">

    <div class="card">

        <h2>Admin Dashboard</h2>

        <p>
            Welcome, {{ auth()->user()->name }}
        </p>

    </div>

    <div class="card">

        <h3>System Summary</h3>

        <p>
            <strong>Total Students:</strong>
            {{ $studentCount }}
        </p>

        <p>
            <strong>Total Subjects:</strong>
            {{ $subjectCount }}
        </p>

        <p>
            <strong>Total Sections:</strong>
            {{ $sectionCount }}
        </p>

        <p>
            <strong>Total Enrollments:</strong>
            {{ $enrollmentCount }}
        </p>

    </div>
</x-layout>