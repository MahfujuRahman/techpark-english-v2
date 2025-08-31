<?php

namespace App\Http\Controllers\Home;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Home\Actions\Home;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model as SuccessStory;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;



class HomeController extends Controller
{
    public function index()
    {
        $course = $this->all_course();
        $data = Home::execute($course);
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

    public function stories()
    {
        $success_stories = SuccessStory::where('status', 1)->orderBy('id', 'desc')->paginate(6);
        return view('frontend.pages.success_stories.success_story_all', compact('success_stories'));
    }
}
