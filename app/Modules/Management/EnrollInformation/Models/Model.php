<?php

namespace App\Modules\Management\EnrollInformation\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
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
            CourseBatchStudent::where('course_id',  $enroll->course_id)
                ->where('batch_id',   $enroll->batch_id)
                ->where('student_id', $enroll->student_id)
                ->delete();
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
