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
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('portal');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (! auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('portal');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/portal', [PortalController::class, 'index'])
        ->name('portal');

    Route::get('/portal/sections/{section}/subjects', [PortalController::class, 'sectionSubjects'])
        ->name('portal.sections.subjects');

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
            '/admin/students/create',
            [StudentController::class, 'create']
        )->name('students.create');

        Route::post(
            '/admin/students',
            [StudentController::class, 'store']
        )->name('students.store');

        Route::get(
            '/admin/students/{student}/edit',
            [StudentController::class, 'edit']
        )->name('students.edit');

        Route::patch(
            '/admin/students/{student}',
            [StudentController::class, 'update']
        )->name('students.update');

        Route::delete(
            '/admin/students/{student}',
            [StudentController::class, 'destroy']
        )->name('students.destroy');

        Route::get(
            '/admin/enrollments',
            [EnrollmentController::class, 'index']
        )->name('enrollments.index');

        Route::patch(
            '/admin/enrollments/bulk-status',
            [EnrollmentController::class, 'bulkStatus']
        )->name('enrollments.bulk-status');

        Route::delete(
            '/admin/enrollments/bulk',
            [EnrollmentController::class, 'bulkDestroy']
        )->name('enrollments.bulk-destroy');

        Route::delete(
            '/admin/enrollments/{enrollment}',
            [EnrollmentController::class, 'destroy']
        )->name('enrollments.destroy');

        Route::patch(
            '/admin/enrollments/{enrollment}/approve',
            [EnrollmentController::class, 'approve']
        )->name('enrollments.approve');

        Route::patch(
            '/admin/enrollments/{enrollment}/reject',
            [EnrollmentController::class, 'reject']
        )->name('enrollments.reject');

        Route::resource('subjects', SubjectController::class);

        Route::post(
            '/admin/subjects/import',
            [SubjectController::class, 'import']
        )->name('subjects.import');

        Route::delete(
            '/admin/subjects/bulk',
            [SubjectController::class, 'bulkDestroy']
        )->name('subjects.bulk-destroy');

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

});

require __DIR__ . '/auth.php';
