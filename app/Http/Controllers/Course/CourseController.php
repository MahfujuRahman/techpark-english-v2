<?php

namespace App\Http\Controllers\Course;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Course\Actions\CourseDetails;
use App\Http\Controllers\Course\Actions\Course as CourseAction;
use App\Modules\Management\UserManagement\User\Models\Model as User;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;


class CourseController extends Controller
{
    public function courses()
    {
        $data = CourseAction::execute();
        return $data;
    }

    public function course_details($slug)
    {
        $data = CourseDetails::execute($slug);
        return $data;
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

    public function course_batch_details($course_id)
    {
        // use a consistent timezone for parsing/comparison; change 'UTC' to config('app.timezone') if you prefer app timezone
        $courseBatch = CourseBatches::active()->orderBy('id', 'DESC')->get();

        $tz = env('TIMEZONE', config('app.timezone', 'UTC'));

        $batch = $courseBatch->where('course_id', $course_id)
            ->filter(function ($b) use ($tz) {
                return !empty($b->admission_end_date) &&
                    Carbon::parse($b->admission_end_date, $tz)->greaterThanOrEqualTo(Carbon::now($tz));
            })
            ->sortBy(function ($b) use ($tz) {
                return Carbon::parse($b->admission_end_date, $tz)->timestamp;
            })
            ->first();

        // If no active batch details found, get the latest batch details
        if (!$batch) {
            $batch = CourseBatches::where('course_id', $course_id)
                ->select(['*'])
                ->active()
                ->orderBy('admission_end_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
        }

        return ['batch' => $batch, 'tz' => $tz];
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
