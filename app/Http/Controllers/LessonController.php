<?php

namespace App\Http\Controllers;

use App\Http\Requests\LessonRequest;
use App\Http\Requests\LessonOrderRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Queries\Lessons\LessonIndexQuery;
use App\Services\Lessons\LessonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        $this->authorize('viewAny', Lesson::class);

        $status = $request->string('status')->value() ?: 'active';

        return view('lessons.index', [
            'lessons' => $this->lessonIndexQuery->paginate($request->user(), $status),
            'status' => $status,
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Lesson::class);

        $lesson = new Lesson();
        $courseId = $request->integer('course_id');

        abort_if($courseId <= 0, 404);

        $course = $this->lessonService->courseForPayload($courseId);
        $this->authorize('manageLessons', $course);
        $lesson->course_id = $course->id;

        return view('lessons.form', $this->lessonService->formData(
            auth()->user(),
            $lesson,
            false,
        ));
    }

    public function store(LessonRequest $request): RedirectResponse
    {
        $this->authorize('create', Lesson::class);

        $course = $this->lessonService->courseForPayload((int) $request->validated('course_id'));
        $this->authorize('manageLessons', $course);

        $lesson = $this->lessonService->create($request->validated());

        return redirect()
            ->route('courses.show', $lesson->course_id)
            ->with('status', 'La lliçó s\'ha creat correctament.');
    }

    public function show(Lesson $lesson): View
    {
        $this->authorize('view', $lesson);
        $lesson->load('course:id,titol,deleted_at');

        return view('lessons.show', [
            'lesson' => $lesson,
        ]);
    }

    public function edit(Lesson $lesson): View
    {
        $this->authorize('update', $lesson);

        return view('lessons.form', $this->lessonService->formData(
            auth()->user(),
            $lesson,
            true,
        ));
    }

    public function update(LessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson);

        $course = $this->lessonService->courseForPayload((int) $request->validated('course_id'));
        $this->authorize('manageLessons', $course);
        $this->lessonService->update($lesson, $request->validated());

        return redirect()
            ->route('courses.show', $lesson->course_id)
            ->with('status', 'La lliçó s\'ha actualitzat correctament.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $this->authorize('delete', $lesson);
        $courseId = $lesson->course_id;
        $this->lessonService->archive($lesson);

        return redirect()
            ->route('courses.show', $courseId)
            ->with('status', 'La lliçó s\'ha enviat a la paperera.');
    }

    public function restore(int $lesson): RedirectResponse
    {
        $lessonModel = Lesson::onlyTrashed()->findOrFail($lesson);
        $this->authorize('restore', $lessonModel);
        $this->lessonService->restore($lessonModel);

        return redirect()
            ->route('lessons.index', ['status' => 'archived'])
            ->with('status', 'La lliçó s\'ha restaurat correctament.');
    }

    public function move(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorize('update', $lesson);

        $direction = $request->string('direction')->value();
        abort_unless(in_array($direction, ['up', 'down'], true), 422);

        $this->lessonService->move($lesson, $direction);

        return redirect()
            ->to(route('courses.show', $lesson->course_id).'#lessons-section')
            ->with('status', 'L\'ordre de les lliçons s\'ha actualitzat correctament.');
    }

    public function reorder(LessonOrderRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('manageLessons', $course);

        $lessonIds = collect($request->validated('lesson_ids'))
            ->mapWithKeys(fn (int $lessonId) => [$lessonId => $lessonId])
            ->all();

        $positions = collect($request->validated('positions'))
            ->mapWithKeys(fn (mixed $position, mixed $lessonId) => [(int) $lessonId => (int) $position])
            ->all();

        $this->lessonService->reorder($course, $positions, array_values($lessonIds));

        return redirect()
            ->route('courses.show', $course)
            ->with('status', 'L\'ordre de les lliçons s\'ha actualitzat correctament.');
    }

}
