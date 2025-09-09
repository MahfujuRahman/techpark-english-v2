<?php

namespace App\Http\Controllers\Course\Actions;


use Carbon\Carbon;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use  App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;
use App\Modules\Management\CourseManagement\Course\Models\WishlistModel;


class CourseDetails
{
    public static function execute($slug)
    {
        $tz = env('TIMEZONE', config('app.timezone', 'UTC'));

        $data = Course::active()->where('slug', $slug)->first();
        $data->is_in_wishlist = self::is_in_wishlist($data->id);

        $instructors = $data->course_instructors()->get();

        $batch_details = $data->course_batch()
            ->where('course_id', $data->id)
            ->whereNotNull('admission_end_date')
            ->where('admission_end_date', '>=', Carbon::now())
            ->select(['*'])
            ->active()
            ->orderBy('admission_end_date', 'ASC')
            ->orderBy('id', 'DESC')
            ->first();

        // If no active batch details found, get the latest batch details
        if (!$batch_details) {
            $batch_details = $data->course_batch()
                ->where('course_id', $data->id)
                ->select(['*'])
                ->active()
                ->orderBy('admission_end_date', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();
        }

        $admissionEndDate = $batch_details->admission_end_date ?? null;

        $formattedDate = null;
        if ($admissionEndDate) {
            $end = Carbon::parse($admissionEndDate, $tz);
            // If stored as a date (midnight), treat it as end of that day (23:59:59)
            if ($end->hour === 0 && $end->minute === 0 && $end->second === 0) {
                $end = $end->endOfDay();
            }
            $formattedDate = $end->format('Y-m-d H:i:s');
        }

        // dd($formattedDate);

        $check_enrolled = false;
        if (auth()->check()) {
            $check_enrolled = EnrollInformation::where('student_id', auth()->user()->id)
                ->where('course_id', $data->id)->exists();
        }
        // dd($check_enrolled, $data, $batch_details);
        // Check if admission has started (using Bangladesh time)
        $is_admission_open = false;
        $is_admission_closed = false;
        $now = Carbon::now($tz);

        // Ensure batch details exist and dates are present before parsing to avoid null property access
        if ($batch_details && !empty($batch_details->admission_start_date)) {
            if ($now->lt(Carbon::parse($batch_details->admission_start_date, $tz))) {
                $is_admission_open = true;
            }
        }

        // Check if admission has ended (using Bangladesh time)
        if ($batch_details && !empty($batch_details->admission_end_date)) {

            $end = Carbon::parse($batch_details->admission_end_date, $tz);
            
            // If stored as a date (midnight), treat it as end of that day (23:59:59)
            if ($end->hour === 0 && $end->minute === 0 && $end->second === 0) {
                $end = $end->endOfDay();
            }

            $formattedDate = $end->format('Y-m-d H:i:s');

            if ($now->gt($formattedDate)) {
                $is_admission_closed = true;
            }
        }

        return view(
            'frontend.pages.courses.course_details',
            [
                'batch_details' => $batch_details,
                'data' => $data,
                'check_enrolled' => $check_enrolled,
                'instructors' => $instructors,
                'formattedDate' => $formattedDate,
                'is_admission_open' => $is_admission_open,
                'is_admission_closed' => $is_admission_closed
            ]
        );
    }

    public static function is_in_wishlist($courseId)
    {
        if (auth()->check()) {
            return WishlistModel::where('user_id', auth()->user()->id)->where('course_id', $courseId)->exists();
        }
        return false;
    }
}
