<?php

namespace App\Http\Controllers;

use App\Enums\CourseLevel;
use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Queries\Courses\CourseIndexQuery;
use App\Services\Courses\CourseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseIndexQuery $courseIndexQuery,
        private readonly CourseService $courseService,
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->value() ?: 'active';
        $selectedLevel = $request->string('nivell')->value();
        $user = $request->user();

        return view('courses.index', [
            'courses' => $this->courseIndexQuery->paginate($user, $status, $selectedLevel),
            'isGuestView' => ! $user,
            'isAdmin' => (bool) $user?->isAdmin(),
            'levels' => CourseLevel::options(),
            'selectedLevel' => $selectedLevel,
            'status' => $status,
        ]);
    }

    public function mine(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->can('courses.create'), 403);

        $status = $request->string('status')->value() ?: 'active';

        return view('courses.mine', [
            'courses' => $this->courseIndexQuery->paginateOwned($user, $status),
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Course::class);

        return view('courses.form', $this->courseService->formData(new Course(), false));
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $course = $this->courseService->create($request->validated(), $request->user());

        return redirect()
            ->route('courses.show', $course)
            ->with('status', 'El curs s\'ha creat correctament.');
    }

    public function show(Request $request, Course $course): View
    {
        return view('courses.show', $this->courseService->showData($request->user(), $course));
    }

    public function edit(Course $course): View
    {
        $this->authorize('update', $course);

        return view('courses.form', $this->courseService->formData($course, true));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('update', $course);
        $this->courseService->update($course, $request->validated());

        return redirect()
            ->route('courses.show', $course)
            ->with('status', 'El curs s\'ha actualitzat correctament.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);
        $this->courseService->archive($course);

        return redirect()
            ->route('courses.index')
            ->with('status', 'El curs s\'ha enviat a la paperera.');
    }

    public function restore(Request $request, int $course): RedirectResponse
    {
        $courseModel = Course::onlyTrashed()->findOrFail($course);
        $this->authorize('restore', $courseModel);

        $this->courseService->restore($courseModel);

        return redirect()
            ->route('courses.index', ['status' => 'archived'])
            ->with('status', 'El curs s\'ha restaurat correctament.');
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $status = $this->courseService->enroll($request->user(), $course);

        return redirect()
            ->route('courses.show', $course)
            ->with('status', $status);
    }
}
