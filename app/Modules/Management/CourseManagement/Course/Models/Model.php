<?php

namespace App\Modules\Management\CourseManagement\Course\Models;


use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Management\CourseManagement\CourseInstructors\Models\Model as CourseInstructors;
use App\Modules\Management\CourseManagement\CourseInstructors\Models\CourseCourseInstructorModel as CourseCourseInstructors;
use App\Modules\Management\CourseManagement\CourseHowIsStructured\Models\Model as CourseHowIsStructured;
use App\Modules\Management\CourseManagement\CourseYouWillLearn\Models\Model as CourseYouWillLearn;
use App\Modules\Management\CourseManagement\CourseForWhom\Models\Model as CourseForWhom;
use App\Modules\Management\CourseManagement\CourseWhyYouLearnFromUs\Models\Model as CourseWhyYouLearnFromUs;

use App\Modules\Management\CourseManagement\Course\Others\SendSubscriberEmail;

class Model extends EloquentModel
{
    use SoftDeletes;
    protected $table = "courses";
    protected $guarded = [];

    public static $course_batch = \App\Modules\Management\CourseManagement\CourseBatch\Models\Model::class;
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

            // Send email to subscribers
            SendSubscriberEmail::dispatch($data);
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
    public function course_category()
    {
        return $this->belongsTo("App\Modules\Management\CourseManagement\CourseCategory\Models\Model", "course_category_id");
    }
    public function course_batch()
    {
        return $this->hasMany(self::$course_batch, 'course_id', 'id');
    }

    public function milestones()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::class, 'course_id', 'id');
    }

    public function modules()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseModule\Models\Model::class, 'course_id', 'id');
    }

    public function classes()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::class, 'course_id', 'id');
    }

    public function course_instructors()
    {
        // use the pivot table name, let Eloquent use the default Pivot model
        return $this->belongsToMany(
            CourseInstructors::class,
            (new CourseCourseInstructors())->getTable(),
            'course_id',
            'instructor_id'
        );
    }

    public function course_how_is_structured()
    {
        return $this->hasMany(CourseHowIsStructured::class, 'course_id');
    }
    public function course_you_will_learn()
    {
        return $this->hasMany(CourseYouWillLearn::class, 'course_id');
    }

    public function course_for_whom()
    {
        return $this->hasMany(CourseForWhom::class, 'course_id');
    }

    public function course_why_you_learn_from_us()
    {
        return $this->hasMany(CourseWhyYouLearnFromUs::class, 'course_id');
    }

    public function course_faqs()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseFaq\Models\Model::class, 'course_id');
    }

    public function course_what_you_will_get()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseWhatYouWillGet\Models\Model::class, 'course_id');
    }

    public function course_essential_requirements()
    {
        return $this->hasMany(\App\Modules\Management\CourseManagement\CourseEssentialRequirement\Models\Model::class, 'course_id');
    }

}
