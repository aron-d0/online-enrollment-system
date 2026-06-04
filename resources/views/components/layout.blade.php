<!DOCTYPE html>
<html>

<head>

    <title>{{ $title ?? 'Online Enrollment System' }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        header {
            background: #1e3a8a;
            color: white;
            padding: 15px 30px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #ddd;
        }

        nav a {
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
            color: #1e3a8a;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        button,
        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background: #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td:first-child,
        table th:first-child {
            width: 180px;
            white-space: nowrap;
        }

        table input,
        table select {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
        }
    </style>

</head>

<body>

    <header>

        <h1>Online Enrollment System</h1>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
                Logout
            </button>
        </form>

    </header>

    @if(auth()->check() && auth()->user()->role === 'admin')
        <nav>
            <a href="/admin">Dashboard</a>

            <a href="/admin/students">Students</a>

            <a href="/subjects">Subjects</a>

            <a href="/sections">Sections</a>

            <a href="/admin/enrollments">Enrollment Reports</a>

            <!-- <a href="/admin/manual-enrollment">
                Manual Enrollment
            </a> -->
        </nav>
    @endif

    <div class="container">

        {{ $slot }}

    </div>

</body>

</html>