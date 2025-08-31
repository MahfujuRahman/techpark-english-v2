<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Modules\Management\UserManagement\User\Models\Model as User;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use  App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;


class CourseController extends Controller
{
    public function courses()
    {
        $course_categories = CourseCategory::where('status', 'active')->get();

        $all = $this->all_course();
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

    public function course_details($slug)
    {
        $data = Course::active()->where('slug', $slug)->first();

        $instructors = $data->course_instructors()->get();

        $batch_details = $data->course_batch()
            ->select([
                'id',
                'course_id',
                'admission_end_date',
                'batch_student_limit',
                'seat_booked',
                'course_price',
                'after_discount_price',
                'booked_percent'
            ])
            ->active()->orderBy('id', 'DESC')->first();

        $check_enrolled = false;
        if (auth()->check()) {
            $check_enrolled = EnrollInformation::where('student_id', auth()->user()->id)
                ->where('course_id', $data->id)->exists();
        }
        return view(
            'frontend.pages.courses.course_details',
            [
                'batch_details' => $batch_details,
                'data' => $data,
                'check_enrolled' => $check_enrolled,
                'instructors' => $instructors
            ]
        );
    }

    public function all_course()
    {
        $course_types = CourseCategory::where('status', 'active')->get();

        if (request()->slug) {
            $slug = request()->slug;

            $courseIds = CourseCategory::where('slug', $slug)->first()->id;

            $courses = Course::active()
                ->with(['course_batch' => function ($batch) {
                    $batch->orderBy('id', 'desc')->first();
                }])
                ->where('course_category_id', $courseIds)
                ->paginate(6);
        } else {
            $courses = Course::active()
                ->with(['course_batch' => function ($batch) {
                    $batch->orderBy('id', 'desc')->take(1);
                }])
                ->paginate(6);
        }

        return ['courses' => $courses, 'course_types' => $course_types];
    }

    public function myCourse()
    {
        $user = User::find(auth()->user()->id);

        $userWithCourses = $user->with([
            'batchStudents' => function ($query) {
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
        // dd($userWithCourses, $completedCourses, $incompleteCourses);

        return view('frontend.pages.mycourse', [
            'user_course' => $userWithCourses->batchStudents,
            'complete_courses' => $completedCourses,
            'incomplete_courses' => $incompleteCourses,
        ]);
    }
}
