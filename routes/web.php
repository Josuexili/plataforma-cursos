<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherApplicationController;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome', [
        'featuredCourses' => Course::query()
            ->with(['creator:id,name'])
            ->withCount('lessons')
            ->orderBy('id')
            ->take(3)
            ->get(['id', 'titol', 'descripcio', 'nivell', 'durada_hores', 'user_id']),
    ]);
})->name('home');

Route::resource('courses', CourseController::class)
    ->only(['index', 'show'])
    ->where(['course' => '[0-9]+']);

Route::get('/dashboard', function () {
    return view('dashboard', [
        'stats' => [
            'courses' => Course::count(),
            'lessons' => Lesson::count(),
            'enrollments' => auth()->user()->can('enrollments.manage.all')
                ? Enrollment::count()
                : Enrollment::where('user_id', auth()->id())->count(),
        ],
        'pendingTeacherApplications' => auth()->user()->can('teacher-requests.review')
            ? \App\Models\User::where('teacher_application_status', 'pending')->count()
            : 0,
        'latestCourses' => Course::latest()->take(3)->get(),
    ]);
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('teacher-applications', [TeacherApplicationController::class, 'store'])->name('teacher-applications.store');
    Route::get('admin/teacher-applications', [TeacherApplicationController::class, 'index'])->name('teacher-applications.index');
    Route::post('admin/teacher-applications/{user}/approve', [TeacherApplicationController::class, 'approve'])->name('teacher-applications.approve');
    Route::post('admin/teacher-applications/{user}/reject', [TeacherApplicationController::class, 'reject'])->name('teacher-applications.reject');

    Route::post('courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('courses/{course}/restore', [CourseController::class, 'restore'])->name('courses.restore');
    Route::resource('courses', CourseController::class)
        ->except(['index', 'show'])
        ->where(['course' => '[0-9]+']);

    Route::resource('enrollments', EnrollmentController::class);
    Route::post('enrollments/{enrollment}/restore', [EnrollmentController::class, 'restore'])->name('enrollments.restore');

    Route::middleware('can:lessons.manage')->group(function () {
        Route::post('lessons/{lesson}/restore', [LessonController::class, 'restore'])->name('lessons.restore');
        Route::resource('lessons', LessonController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
