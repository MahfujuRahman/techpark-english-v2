<?php

namespace App\Http\Controllers\Course\Actions;

use App\Modules\Management\UserManagement\User\Models\Model as User;

class MyCourse
{
    public static function execute()
    {
        $user = User::find(auth()->user()->id);

        $userWithCourses = $user->with([
            'batchStudents' => function ($query): void {
                $query->select('id', 'course_id', 'batch_id', 'student_id', 'course_percent', 'is_complete');
            },
            'batchStudents.course' => function ($query) {
                $query->select('id', 'title', 'image', 'slug');
            },
            'batchStudents.batch' => function ($q2) {
                $q2->select('id', 'batch_name', 'class_days', 'class_start_time', 'class_end_time');
            }
        ])->find($user->id);

        // Use collection methods to split courses based on 'is_complete'
        $completedCourses = $userWithCourses->batchStudents->where('is_complete', 'complete');
        $incompleteCourses = $userWithCourses->batchStudents->where('is_complete', 'incomplete');

        return view('frontend.pages.courses.mycourse', [
            'user_course' => $userWithCourses->batchStudents,
            'complete_courses' => $completedCourses,
            'incomplete_courses' => $incompleteCourses,
        ]);
    }
}
