<?php

namespace App\Modules\Management\QuizManagement\QuizSubmissionResult\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "quiz_submission_results";
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
    public function quiz_id()
    {
        return $this->belongsTo("App\Modules\Management\QuizManagement\Quiz\Models\Model", "quiz_id");
    }
    public function course_module_class_id()
    {
        return $this->belongsTo("App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model", "course_module_class_id");
    }
    public function user_id()
    {
        return $this->belongsTo("App\Modules\Management\UserManagement\User\Models\Model", "user_id");
    }
}
