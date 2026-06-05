<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    if (auth()->check()) {

        if (auth()->user()->role === 'admin') {
            return redirect('/admin');
        }

        return redirect('/portal');
    }

    return redirect('/login');
});

Route::middleware('auth')->group(function () {

    Route::get('/portal', [PortalController::class, 'index'])
        ->name('portal');

    Route::post('/enroll', [EnrollmentController::class, 'store'])
        ->name('enroll.store');

    Route::middleware('admin')->group(function () {

        Route::get('/admin', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::get(
            '/admin/students',
            [StudentController::class, 'index']
        )->name('students.index');

        Route::get(
            '/admin/enrollments',
            [EnrollmentController::class, 'index']
        )->name('enrollments.index');

        Route::delete(
            '/admin/enrollments/{enrollment}',
            [EnrollmentController::class, 'destroy']
        )->name('enrollments.destroy');

        // Route::get(
        //     '/admin/manual-enrollment',
        //     [EnrollmentController::class, 'create']
        // )->name('enrollments.create');

        // Route::post(
        //     '/admin/manual-enrollment',
        //     [EnrollmentController::class, 'adminStore']
        // )->name('enrollments.admin.store');

        Route::patch(
            '/admin/enrollments/{enrollment}/approve',
            [EnrollmentController::class, 'approve']
        )->name('enrollments.approve');

        Route::patch(
            '/admin/enrollments/{enrollment}/reject',
            [EnrollmentController::class, 'reject']
        )->name('enrollments.reject');

        Route::resource('subjects', SubjectController::class);

        Route::resource('sections', SectionController::class);

    });

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get(
        '/admin/enrollments/export/json',
        [EnrollmentController::class, 'exportJson']
    )->name('enrollments.export.json');

    Route::get(
        '/admin/enrollments/export/csv',
        [EnrollmentController::class, 'exportCsv']
    )->name('enrollments.export.csv');

    Route::post(
        '/admin/subjects/import',
        [SubjectController::class, 'import']
    )->name('subjects.import');
});

require __DIR__ . '/auth.php';