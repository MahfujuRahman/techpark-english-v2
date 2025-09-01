<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\CourseManagement\CourseBatchStudent\Models\Model as CourseBatchStudent;

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
        $this->validate(request(), [
            "trx_id" => ["required"],
        ]);

        $course = Course::active()->where('slug', $slug)->select('id', 'slug', 'title')->first();
        $batch = CourseBatches::active()->where('course_id', $course->id)
            ->orderBy('id', 'DESC')->select('id', 'batch_name')->first();

        $course_std_check = CourseBatchStudent::where('student_id', auth()->user()->id)
            ->where('batch_id', $batch->id)->where('course_id', $course->id)->exists();

        if (!$course_std_check) {
            $enroll_payment = new EnrollInformation();
            $enroll_payment->course_id = $course->id;
            $enroll_payment->student_id = auth()->user()->id;
            $enroll_payment->batch_id = $batch->id;
            $enroll_payment->trx_id = request()->trx_id;
            $enroll_payment->payment_type = 'online';
            $enroll_payment->save();

            $course_batch_student = new CourseBatchStudent();
            $course_batch_student->course_id = $enroll_payment->course_id;
            $course_batch_student->batch_id = $enroll_payment->batch_id;
            $course_batch_student->student_id = $enroll_payment->student_id;
            $course_batch_student->status = 'active';
            $course_batch_student->save();
            return redirect('/')->with('success', 'Course Enrolled Successfully!');
        } else {
            return redirect()->back()->with('warning', 'You are already enrolled!');
        }
    }
}
