<?php

namespace App\Http\Controllers\Course\Actions;


use Carbon\Carbon;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use  App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;


class CourseDetails
{
    public static function execute($slug)
    {
        $data = Course::active()->where('slug', $slug)->first();

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

        $formattedDate = $admissionEndDate
            ? Carbon::parse($admissionEndDate)->format('Y-m-d H:i:s')
            : null;

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
                'instructors' => $instructors,
                'formattedDate' => $formattedDate
            ]
        );
    }
}
