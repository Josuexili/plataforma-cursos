<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Queries\Lessons\LessonIndexQuery;
use App\Services\Lessons\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(
        private readonly LessonIndexQuery $lessonIndexQuery,
        private readonly LessonService $lessonService,
    ) {
    }

    public function index(Request $request): View
    {
        $this->authorizeLessonManagement($request);

        $status = $request->string('status')->value() ?: 'active';

        return view('lessons.index', [
            'lessons' => $this->lessonIndexQuery->paginate($request->user(), $status),
            'status' => $status,
        ]);
    }

    public function create(): View
    {
        $this->authorizeLessonManagement();

        return view('lessons.form', $this->lessonService->formData(
            auth()->user(),
            new Lesson(),
            false,
        ));
    }

    public function store(LessonRequest $request): RedirectResponse
    {
        $this->authorizeLessonManagement($request);

        $course = $this->lessonService->courseForPayload((int) $request->validated('course_id'));
        Gate::authorize('is-owner', $course);

        $lesson = $this->lessonService->create($request->validated());

        return redirect()
            ->route('lessons.show', $lesson)
            ->with('status', 'La lliçó s\'ha creat correctament.');
    }

    public function show(Lesson $lesson): View
    {
        $this->authorizeLessonManagement();
        Gate::authorize('is-owner', $lesson->course);
        $lesson->load('course:id,titol,deleted_at');

        return view('lessons.show', [
            'lesson' => $lesson,
        ]);
    }

    public function edit(Lesson $lesson): View
    {
        $this->authorizeLessonManagement();
        Gate::authorize('is-owner', $lesson->course);

        return view('lessons.form', $this->lessonService->formData(
            auth()->user(),
            $lesson,
            true,
        ));
    }

    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $this->authorizeLessonManagement($request);
        Gate::authorize('is-owner', $lesson->course);

        $course = $this->lessonService->courseForPayload((int) $request->validated('course_id'));
        Gate::authorize('is-owner', $course);
        $this->lessonService->update($lesson, $request->validated());

        return redirect()
            ->route('lessons.show', $lesson)
            ->with('status', 'La lliçó s\'ha actualitzat correctament.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $this->authorizeLessonManagement();
        Gate::authorize('is-owner', $lesson->course);
        $this->lessonService->archive($lesson);

        return redirect()
            ->route('lessons.index')
            ->with('status', 'La lliçó s\'ha enviat a la paperera.');
    }

    public function restore(int $lesson): RedirectResponse
    {
        $this->authorizeLessonManagement();

        $lessonModel = Lesson::onlyTrashed()->findOrFail($lesson);
        Gate::authorize('is-owner', $lessonModel->course);
        $this->lessonService->restore($lessonModel);

        return redirect()
            ->route('lessons.index', ['status' => 'archived'])
            ->with('status', 'La lliçó s\'ha restaurat correctament.');
    }

    private function authorizeLessonManagement(?Request $request = null): void
    {
        $user = $request?->user() ?? auth()->user();
        abort_unless($user->can('lessons.manage'), 403);
    }
}
