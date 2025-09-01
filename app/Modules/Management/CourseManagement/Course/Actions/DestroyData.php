<?php

namespace App\Modules\Management\CourseManagement\Course\Actions;

use Illuminate\Support\Facades\DB;
use App\Modules\Management\QuizManagement\Quiz\Actions\DestroyData as QuizDestroyData;

class DestroyData
{
    static $model = \App\Modules\Management\CourseManagement\Course\Models\Model::class;
    static $milestoneModel = \App\Modules\Management\CourseManagement\CourseMilestone\Models\Model::class;
    static $moduleModel = \App\Modules\Management\CourseManagement\CourseModule\Models\Model::class;
    static $classModel = \App\Modules\Management\CourseManagement\CourseModuleClass\Models\Model::class;
    static $batchModel = \App\Modules\Management\CourseManagement\CourseBatch\Models\Model::class;
    static $batchStudentModel = \App\Modules\Management\CourseManagement\CourseBatchStudent\Models\Model::class;
    static $courseInstructorModel = \App\Modules\Management\CourseManagement\CourseCourseInstructor\Models\Model::class;
    static $faqModel = \App\Modules\Management\CourseManagement\CourseFaq\Models\Model::class;
    static $forWhomModel = \App\Modules\Management\CourseManagement\CourseForWhom\Models\Model::class;
    static $howIsStructuredModel = \App\Modules\Management\CourseManagement\CourseHowIsStructured\Models\Model::class;
    static $classRoutineModel = \App\Modules\Management\CourseManagement\CourseModuleClassRoutine\Models\Model::class;
    static $whyYouLearnFromUsModel = \App\Modules\Management\CourseManagement\CourseWhyYouLearnFromUs\Models\Model::class;
    static $youWillLearnModel = \App\Modules\Management\CourseManagement\CourseYouWillLearn\Models\Model::class;
    static $whatyouwillgetModel = \App\Modules\Management\CourseManagement\CourseWhatYouWillGet\Models\Model::class;
    static $course_modules_class_quizzes = \App\Modules\Management\CourseManagement\CourseModuleClassQuiz\Models\Model::class;
    static $quiz_submissions = \App\Modules\Management\QuizManagement\Quiz\Models\QuizSubmissionModel::class;
    static $quiz_submission_results = \App\Modules\Management\QuizManagement\QuizSubmissionResult\Models\Model::class;

    public static function execute($slug)
    {
        try {

            if (!$data = self::$model::where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }


            // Use DB transaction for safe deletion
            DB::beginTransaction();
            
            if ($data->image) {
                $imagePath = public_path($data->image);

                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            // Delete related course data
            self::$milestoneModel::where('course_id', $data->id)->forceDelete();
            self::$moduleModel::where('course_id', $data->id)->forceDelete();
            self::$classModel::where('course_id', $data->id)->forceDelete();
            self::$batchModel::where('course_id', $data->id)->forceDelete();
            self::$batchStudentModel::where('course_id', $data->id)->forceDelete();
            self::$courseInstructorModel::where('course_id', $data->id)->forceDelete();
            self::$faqModel::where('course_id', $data->id)->forceDelete();
            self::$forWhomModel::where('course_id', $data->id)->forceDelete();
            self::$howIsStructuredModel::where('course_id', $data->id)->forceDelete();
            self::$classRoutineModel::where('course_id', $data->id)->forceDelete();
            self::$whyYouLearnFromUsModel::where('course_id', $data->id)->forceDelete();
            self::$youWillLearnModel::where('course_id', $data->id)->forceDelete();
            self::$whatyouwillgetModel::where('course_id', $data->id)->forceDelete();

            // Delete course related quizzes
            self::$course_modules_class_quizzes::where('course_id', $data->id)->forceDelete();
            self::$quiz_submissions::where('course_id', $data->id)->forceDelete();
            self::$quiz_submission_results::where('course_id', $data->id)->forceDelete();

            $data->forceDelete();
            DB::commit();

            return messageResponse('Item Successfully deleted', [], 200, 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
