<?php

namespace App\Http\Controllers\Course\Actions;


use Carbon\Carbon;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Http\Controllers\Course\CourseController;

class Course
{
    public static function execute()
    {
        $course_categories = CourseCategory::where('status', 'active')->get();

        $controller = new CourseController;

        $all = $controller->all_course();
        $courses = $all['courses']; 
        $course_types = $all['course_types'];

        $courseBatch = CourseBatches::active()->orderBy('id', 'DESC')->get();

        return view('frontend.pages.courses.index', [
            'course_categories' => $course_categories,
            'course_types' => $course_types,
            'courses' => $courses,
            'courseBatches' => $courseBatch
        ]);
    }
}
