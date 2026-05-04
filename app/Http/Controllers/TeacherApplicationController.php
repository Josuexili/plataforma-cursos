<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Queries\TeacherApplications\PendingTeacherApplicationsQuery;
use App\Services\TeacherApplications\TeacherApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherApplicationController extends Controller
{
    public function __construct(
        private readonly PendingTeacherApplicationsQuery $pendingTeacherApplicationsQuery,
        private readonly TeacherApplicationService $teacherApplicationService,
    ) {
    }

    public function index(): View
    {
        abort_unless(auth()->user()->can('teacher-requests.review'), 403);

        return view('admin.teacher-applications.index', [
            'pendingUsers' => $this->pendingTeacherApplicationsQuery->paginate(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        return back()->with('status', $this->teacherApplicationService->submit($request->user()));
    }

    public function approve(User $user): RedirectResponse
    {
        $this->authorizePendingTeacherRequest($user);

        return back()->with('status', $this->teacherApplicationService->approve($user));
    }

    public function reject(User $user): RedirectResponse
    {
        $this->authorizePendingTeacherRequest($user);

        return back()->with('status', $this->teacherApplicationService->reject($user));
    }

    private function authorizePendingTeacherRequest(User $user): void
    {
        abort_unless(auth()->user()->can('teacher-requests.review'), 403);
        abort_unless(
            $user->hasRole('student') && $user->teacher_application_status === 'pending',
            404
        );
    }
}
