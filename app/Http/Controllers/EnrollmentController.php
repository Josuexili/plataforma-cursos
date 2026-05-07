<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use App\Models\Enrollment;
use App\Queries\Enrollments\EnrollmentIndexQuery;
use App\Services\Enrollments\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function __construct(
        private readonly EnrollmentIndexQuery $enrollmentIndexQuery,
        private readonly EnrollmentService $enrollmentService,
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->value() ?: 'active';

        return view('enrollments.index', [
            'enrollments' => $this->enrollmentIndexQuery->paginate($request->user(), $status),
            'status' => $status,
            'isAdmin' => $request->user()->can('enrollments.manage.all'),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Enrollment::class);
        $canManageAll = $request->user()->can('enrollments.manage.all');

        return view('enrollments.form', $this->enrollmentService->formData(
            $request->user(),
            new Enrollment(['user_id' => $request->user()->id]),
            false,
            $canManageAll,
        ));
    }

    public function store(EnrollmentRequest $request): RedirectResponse
    {
        $this->authorize('create', Enrollment::class);
        $result = $this->enrollmentService->store(
            $request->validated(),
            $request->user(),
            $request->user()->can('enrollments.manage.all'),
        );

        return redirect()
            ->route('enrollments.show', $result['enrollment'])
            ->with('status', $result['reactivated']
                ? 'La inscripció ja existia i s\'ha reactivat correctament.'
                : 'La inscripció s\'ha creat correctament.');
    }

    public function show(Request $request, Enrollment $enrollment): View
    {
        $this->authorize('view', $enrollment);
        $enrollment->load(['user', 'course']);

        return view('enrollments.show', [
            'enrollment' => $enrollment,
        ]);
    }

    public function edit(Request $request, Enrollment $enrollment): View
    {
        $canManageAll = $request->user()->can('enrollments.manage.all');
        $this->authorize('update', $enrollment);

        return view('enrollments.form', $this->enrollmentService->formData(
            $request->user(),
            $enrollment,
            true,
            $canManageAll,
        ));
    }

    public function update(EnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $this->authorize('update', $enrollment);
        $this->enrollmentService->update(
            $enrollment,
            $request->validated(),
            $request->user(),
            $request->user()->can('enrollments.manage.all'),
        );

        return redirect()
            ->route('enrollments.show', $enrollment)
            ->with('status', 'La inscripció s\'ha actualitzat correctament.');
    }

    public function destroy(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $this->authorize('delete', $enrollment);
        $this->enrollmentService->archive($enrollment);

        return redirect()
            ->route('enrollments.index')
            ->with('status', 'La inscripció s\'ha enviat a la paperera.');
    }

    public function restore(Request $request, int $enrollment): RedirectResponse
    {
        $enrollmentModel = Enrollment::onlyTrashed()->findOrFail($enrollment);
        $this->authorize('restore', $enrollmentModel);
        $this->enrollmentService->restore($enrollmentModel);

        return redirect()
            ->route('enrollments.index', ['status' => 'archived'])
            ->with('status', 'La inscripció s\'ha restaurat correctament.');
    }
}
