<?php

namespace App\Http\Controllers\Course;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\CourseManagement\CourseBatchStudent\Models\Model as CourseBatchStudent;
use Illuminate\Validation\Rules\Can;

class CourseEnrollController extends Controller
{
    public function course_enroll($slug)
    {
        $course = Course::active()
            ->where('slug', $slug)
            ->select('id', 'title', 'slug', 'image')
            ->first();

        return view('frontend.pages.course_enroll.index', ['course' => $course]);
    }

    public function course_enroll_submit($slug)
    {
        $tz = env('TIMEZONE', config('app.timezone', 'UTC'));

        $course = Course::active()->where('slug', $slug)->select('id', 'slug', 'title')->first();

        // Retrieve course batch details using the centralized CourseController method
        $course_controller = new CourseController();
        $data = $course_controller->course_batch_details($course->id);
        $batch = $data['batch'];

        // Check if admission has started (using Bangladesh time)
        $now = Carbon::now($tz);
        if ($now->lt(Carbon::parse($batch->admission_start_date, $tz))) {
            return redirect()->back()->with('error', 'Admission has not started yet.');
        }

        // Check if admission has ended (using Bangladesh time)
        if ($now->gt(Carbon::parse($batch->admission_end_date, $tz))) {
            return redirect()->back()->with('error', 'Admission period has ended.');
        }

        // Check if the user is already enrolled in the course
        $course_std_check = CourseBatchStudent::where('student_id', auth()->user()->id)
            ->where('batch_id', $batch->id)
            ->where('course_id', $course->id)
            ->exists();


        $total = round($batch->after_discount_price ? $batch->after_discount_price : 0);


        if (!$course_std_check) {
            //trigger sslcommerz payment
            return redirect()->route(
                'payment.order',
                [
                    'total' => $total,
                    'course_slug' => $course->slug,
                    'batch_id' => $batch->id,
                    'customer_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    'customer_email' => auth()->user()->email,
                ]
            );
        } else {
            return redirect()->back()->with('error', 'You are already enrolled!');
        }
    }
}
