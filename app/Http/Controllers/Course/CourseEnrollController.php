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
        $course = Course::active()->where('slug', $slug)->select('id', 'slug', 'title')->first();

        $course_controller = new CourseController();
        $data = $course_controller->course_batch_details($course->id);
        $batch = $data['batch'];

        $course_std_check = CourseBatchStudent::where('student_id', auth()->user()->id)
            ->where('batch_id', $batch->id)
            ->where('course_id', $course->id)
            ->exists();

        $subtotal = $batch->course_price ? $batch->course_price : 0;
        $discount = $batch->course_discount ? $batch->course_discount : 0;
        $total = round($batch->after_discount_price ? $batch->after_discount_price : 0);

        if (!$course_std_check) {
            try {
                return DB::transaction(function () use ($course, $batch, $subtotal, $discount, $total) {

                    $orderData = [
                        'order_no' => time() . rand(100, 999),
                        'user_id' => auth()->user() ? auth()->user()->id : null,
                        'order_date' => date("Y-m-d H:i:s"),
                        'payment_method' => 1, //sslcommerz
                        'payment_status' => 0, //unpaid
                        'trx_id' => time() . Str::random(5),
                        'sub_total' => $subtotal,
                        'discount' => $discount,
                        'total' => $total,
                        'slug' => Str::slug($course->title . '-' . time() . '-' . Str::random(6))
                    ];

                    $orderId = DB::table('orders')->insertGetId($orderData);
                    $orderData['id'] = $orderId;

                    DB::table('order_details')->insert([
                        'order_id' => $orderId,
                        'product_id' => $batch->id,
                        'qty' => 1,
                        'unit_price' => $total,
                        'total_price' => $total,
                    ]);

                    DB::table('enroll_informations')->insert([
                        'course_id' => $course->id,
                        'student_id' => auth()->user()->id,
                        'batch_id' => $batch->id,
                        'trx_id' =>  $orderData['trx_id'],
                        'payment_type' => 'online',
                        'payment_by' => auth()->user()->id,
                        'payment_status' => 'unpaid',
                        'total_amount' => $total,
                        'paid_amount' => 0,
                        'slug' => Str::slug($course->title . '-' . time() . '-' . Str::random(6))

                    ]);

                    DB::table('course_batch_students')->insert([
                        'course_id' => $course->id,
                        'batch_id' => $batch->id,
                        'student_id' => auth()->user()->id,
                        'is_complete' => 'incomplete',
                        'slug' => Str::slug($course->title . '-' . time() . '-' . Str::random(6))

                    ]);

                    session([
                        'course_id' => $course->slug,
                        'order_id' => $orderId,
                        'customer_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                        'customer_email' => auth()->user()->email,
                    ]);

                    //trigger sslcommerz payment
                    return redirect('sslcommerz/order');
                });
            } catch (\Exception $e) {
                // Log the error for debugging
                Log::error('Course enrollment failed: ' . $e->getMessage());

                return redirect()->back()->with('error', 'Enrollment failed. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', 'You are already enrolled!');
        }
    }
}
