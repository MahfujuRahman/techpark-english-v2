<?php

namespace App\Http\Controllers\Course\Actions;

use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use  App\Modules\Management\EnrollInformation\Models\Model as EnrollInformation;
use App\Http\Controllers\Course\CourseController;
use App\Modules\Management\CourseManagement\CourseModuleClass\Models\CourseModuleTaskCompleteByUserModel;

class MyCourseDetails
{
    public static function execute($slug)
    {
        $data = Course::active()->where('slug', $slug)->select('id', 'title')->first();

        $controller = new CourseController;
        $data->routines = $controller->routine_details($data->id);

        $data->milestones = $data->milestones()->orderBy('milestone_no', 'ASC')->get();

        foreach ($data->milestones as $key => $mileStones) {

            $modules = $mileStones->modules()->orderBy('module_no', 'ASC')->get();
            $mileStones->modules = $modules;

            foreach ($mileStones->modules as $key => $module) {

                $classes = $module->classes()->get();
                foreach ($classes as $key => $class) {

                    $class_watched_check = CourseModuleTaskCompleteByUserModel::where('course_id', $data->id)
                        ->where('module_id', $module->id)
                        ->where('class_id', $class->id)
                        ->where('user_id', auth()->user()->id)
                        ->where('quiz_id', null)
                        ->where('exam_id', null)
                        ->first();

                    if ($key == 0) {
                        $class->is_complete = true;
                    } else {
                        $class->is_complete = false;
                        if ($class_watched_check != null) {
                            $class->is_complete = true;
                        }
                    }

                    $class_quiz = $class->quizes()->with(['quiz'])->orderBy('id', 'DESC')->first();
                    // $class_exam = $class->class_exam()->with(['exam'])->orderBy('id', 'DESC')->first();

                    if ($class_quiz != null) {
                        $quiz_complete_check = CourseModuleTaskCompleteByUserModel::where('course_id', $data->id)
                            ->where('class_id', $class->id)
                            ->where('module_id', $module->id)
                            ->where('user_id', auth()->user()->id)
                            ->where('quiz_id', $class_quiz->quiz_id)
                            ->first();

                        $class->class_quiz = $class_quiz;

                        $class->class_quiz->is_complete = false;
                        if ($quiz_complete_check != null) {
                            $class->class_quiz->is_complete = true;
                        }
                    }

                    // if ($class_exam != null) {
                    //     $exam_complete_check = CourseModuleTaskCompleteByUserModel::where('class_id', $class->id)
                    //         ->where('course_id', $data->id)
                    //         ->where('module_id', $module->id)
                    //         ->where('user_id', auth()->user()->id)
                    //         ->where('exam_id', $class_exam->exam_id)
                    //         ->first();

                    //     $class->class_exam = $class_exam;

                    //     $class->class_exam->is_complete = false;
                    //     if ($exam_complete_check != null) {
                    //         $class->class_exam->is_complete = true;
                    //     }
                    // }
                }

                $module->classes = $classes;
                // $data->course_module[$key] = $module;
            }
        }

        // dd($data->toArray());
        $total_class_attend = CourseModuleTaskCompleteByUserModel::where('course_id', $data->id)
            ->where('user_id', auth()->user()->id)
            ->count();

        $total_class = $data->milestones->sum(function ($milestone) {
            return $milestone->modules->sum(function ($module) {
                return $module->classes->count();
            });
        });

        $progress = round(($total_class_attend / $total_class) * 100, 2);
    
        return view('frontend.pages.courses.my_course_details', ['course' => $data, 'progress' => $progress]);
    }
}
