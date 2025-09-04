<?php

namespace App\Modules\Management\EnrollInformation\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use App\Modules\Management\CourseManagement\CourseBatchStudent\Models\Model as CourseBatchStudent;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "enroll_informations";
    protected $guarded = [];
    protected static function booted()
    {
        static::created(function ($data) {
            $random_no = random_int(100, 999) . $data->id . random_int(100, 999);
            $slug = $data->title . " " . $random_no;
            $data->slug = Str::slug($slug); //use Illuminate\Support\Str;
            if (strlen($data->slug) > 50) {
                $data->slug = substr($data->slug, strlen($data->slug) - 50, strlen($data->slug));
            }
            if (auth()->check()) {
                $data->creator = auth()->user()->id;
            }
            $data->save();
        });

        // Update the existing pivot row using ORIGINAL keys
        static::updating(function ($enroll) {
            $orig = $enroll->getOriginal(); // original values before change

            $affected = CourseBatchStudent::where('course_id', $orig['course_id'])
                ->where('batch_id',   $orig['batch_id'])
                ->where('student_id', $orig['student_id'])
                ->update([
                    'course_id'  => $enroll->course_id,
                    'batch_id'   => $enroll->batch_id,
                    'student_id' => $enroll->student_id,
                    'status'     => $enroll->status ?? 'active',
                    'course_percent' => $enroll->course_percent ?? 0,
                ]);

            // If nothing was updated (e.g., missing pivot due to past inserts), create it now
            if ($affected === 0) {
                CourseBatchStudent::updateOrCreate(
                    [
                        'course_id'  => $enroll->course_id,
                        'batch_id'   => $enroll->batch_id,
                        'student_id' => $enroll->student_id,
                    ],
                    [
                        'status' => $enroll->status ?? 'active',
                        'course_percent' => $enroll->course_percent ?? 0,
                    ]
                );
            }
        });

        // Remove on delete
        static::deleted(function ($enroll) {
            // Delete course_batch_students
            DB::table('course_batch_students')
                ->where('course_id', $enroll->course_id)
                ->where('batch_id', $enroll->batch_id)
                ->where('student_id', $enroll->student_id)
                ->delete();

            // Delete orders and cascade order_details + order_payments
            $orders = DB::table('orders')
                ->where('trx_id', $enroll->trx_id)
                ->pluck('id');

            if ($orders->count() > 0) {
                DB::table('order_details')->whereIn('order_id', $orders)->delete();
                DB::table('order_payments')->whereIn('order_id', $orders)->delete();
                DB::table('orders')->whereIn('id', $orders)->delete();
            }
        });
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeInactive($q)
    {
        return $q->where('status', 'inactive');
    }
    public function scopeTrased($q)
    {
        return $q->onlyTrashed();
    }
    public function course_id()
    {
        return $this->belongsTo("App\Modules\Management\CourseManagement\Course\Models\Model", "course_id");
    }
    public function student_id()
    {
        return $this->belongsTo("App\Modules\Management\UserManagement\User\Models\Model", "student_id");
    }
    public function batch_id()
    {
        return $this->belongsTo("App\Modules\Management\CourseManagement\CourseBatch\Models\Model", "batch_id");
    }
}
