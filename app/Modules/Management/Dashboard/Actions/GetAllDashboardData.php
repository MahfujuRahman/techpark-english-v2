<?php

namespace App\Modules\Management\Dashboard\Actions;

class GetAllDashboardData
{


    public static function execute()
    {
        try {
            $data = [
                'total_students' => \App\Modules\Management\UserManagement\User\Models\Model::where('role_id', config('roleManagement.student'))->count(),
                'total_courses' => \App\Modules\Management\CourseManagement\Course\Models\Model::count(),
                'total_instructors' => \App\Modules\Management\CourseManagement\CourseInstructors\Models\Model::count(),
                'total_quizzes' => \App\Modules\Management\QuizManagement\Quiz\Models\Model::count(),
                'total_blogs' => \App\Modules\Management\BlogManagement\Blog\Models\Model::count(),
                'total_enrollments' => \App\Modules\Management\EnrollInformation\Models\Model::count(),
                'total_seminars' => \App\Modules\Management\SeminerManagement\Seminer\Models\Model::count(),
                'total_subscribers' => \App\Modules\Management\CommunicationManagement\Subscriber\Models\Model::count(),
            ];

            // Return plain array instead of Response
            return $data;
        } catch (\Exception $e) {
            // You can throw exception or return error array
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }
}
