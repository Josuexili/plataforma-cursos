<?php

namespace App\Support\Permissions;

class PlatformPermissions
{
    public const COURSES_CREATE = 'courses.create';
    public const COURSES_UPDATE_OWN = 'courses.update.own';
    public const COURSES_RESTORE_OWN = 'courses.restore.own';
    public const COURSES_VIEW_ENROLLMENTS = 'courses.view.enrollments';
    public const LESSONS_MANAGE = 'lessons.manage';
    public const ENROLLMENTS_MANAGE_OWN = 'enrollments.manage.own';
    public const ENROLLMENTS_MANAGE_ALL = 'enrollments.manage.all';
    public const TEACHER_REQUESTS_REVIEW = 'teacher-requests.review';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::COURSES_CREATE,
            self::COURSES_UPDATE_OWN,
            self::COURSES_RESTORE_OWN,
            self::COURSES_VIEW_ENROLLMENTS,
            self::LESSONS_MANAGE,
            self::ENROLLMENTS_MANAGE_OWN,
            self::ENROLLMENTS_MANAGE_ALL,
            self::TEACHER_REQUESTS_REVIEW,
        ];
    }

    /**
     * @return list<string>
     */
    public static function forStudent(): array
    {
        return [
            self::ENROLLMENTS_MANAGE_OWN,
        ];
    }

    /**
     * @return list<string>
     */
    public static function forTeacher(): array
    {
        return [
            self::COURSES_CREATE,
            self::COURSES_UPDATE_OWN,
            self::COURSES_RESTORE_OWN,
            self::COURSES_VIEW_ENROLLMENTS,
            self::LESSONS_MANAGE,
            self::ENROLLMENTS_MANAGE_OWN,
        ];
    }
}
