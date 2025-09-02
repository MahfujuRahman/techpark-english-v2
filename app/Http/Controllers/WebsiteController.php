<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Quiz\Quiz;
use App\Models\Blog\Blogs;
use App\Models\ItServices;
use App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model as SuccessStory;
use Illuminate\Http\Request;
use App\Models\Blog\BlogTags;
use App\Modules\Management\CourseManagement\Course\Models\Model as Course;
use App\Models\Quiz\QuizUser;
use App\Models\ContactMessage;
use App\Models\GalleryCategory;
use App\Models\Blog\BlogWriters;
use App\Models\CareerCounseling;
use App\Modules\Management\WebsiteManagement\OurSpeciality\Models\Model as OurSpeciality;
use App\Modules\Management\CourseManagement\Course\Models\Model as CourseOutcomeStepModel;
use App\Models\EnrollInformation;
use App\Models\Quiz\QuizQuestion;
use Illuminate\Support\Facades\DB;
use App\Models\Blog\BlogsCategories;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Models\WebsiteCoreInformation;
use App\Models\Quiz\QuizQuestionOption;
use App\Models\Course\CourseInstructors;
use App\Models\Course\CourseBatchStudent;
use Illuminate\Support\Facades\Validator;
use App\Models\Quiz\QuizQuestionSubmission;
use App\Modules\Management\SeminerManagement\Seminer\Models\Model as Seminars;
use App\Models\Course\CourseModuleClassRoutines;
use App\Models\Course\CourseModuleTaskCompleteByUsers;
use App\Modules\Management\CourseManagement\CourseCategory\Models\Model as CourseCategory;
use App\Modules\Management\WebsiteManagement\WebsiteBanner\Models\Model as Banner;
use App\Modules\Management\WebsiteManagement\SubBanner\Models\Model as SubBanner;
use App\Modules\Management\WebsiteManagement\OurTrainer\Models\Model as OurTrainer;
use App\Modules\Management\WebsiteManagement\WebsiteBrand\Models\Model as Brand;

class WebsiteController extends Controller
{

    public function all_types()
    {
        return CourseType::active()->get();
    }

    public function all_course()
    {
        dd(request()->slug);
        $course_types = CourseCategory::where('status', 'active')->get();

        if (request()->slug) {
            $slug = request()->slug;
            $courseIds = CourseCategory::where('slug', $slug)->first()->id;
            dd($courseIds);
            $courses = Course::active()
                ->with(['course_batch' => function ($batch) {
                    $batch->orderBy('id', 'desc')->first();
                }])
                ->whereIn('id', $courseIds)
                ->paginate(6);
        } else {
            $courses = Course::active()
                ->with(['course_batch' => function ($batch) {
                    $batch->orderBy('id', 'desc')->take(1);
                }])
                ->paginate(6);
        }

        return ['courses' => $courses, 'course_types' => $course_types];
    }


    public function type_wise_course()
    {
        // $courses = Course
        $seminar = Seminars::whereDate('date_time', '>', Carbon::today())->get();
        return view('frontend.home', compact('seminar'));
    }



    public function it_solution_services()
    {
        $it_services = ItServices::get();
        return view('frontend.pages.it_solution_services', [
            'it_services' => $it_services
        ]);
    }


    public function quizes()
    {

        // $quiz = Quiz::where('id', 1)->with([
        //     'questions' => function ($q) {
        //         $q->with([
        //             'options' => function ($q) {
        //                 $q->select(['id', 'quiz_id', 'question_id', 'title']);
        //             },
        //         ]);
        //     }
        // ])->first();
        // return response()->json($quiz);

        $user = auth()->user();

        $isAttemtQuiz = QuizQuestionSubmission::where('user_id', $user->id)->where('quiz_id', request()->quiz_id)->first();
        if ($isAttemtQuiz->quiz_id == request()->quiz_id) {
            return redirect()->route('website.quizes');
        }

        $quizes = Quiz::select(['id', 'title'])->get();


        return view('frontend.pages.quiz', compact('quizes'));
    }

    public function quiz_attend($id)
    {
        // $this->middleware('api:auth');

        $quiz = Quiz::where('id', $id)->with([
            'questions' => function ($q) {
                $q->with([
                    'options' => function ($q) {
                        $q->select(['id', 'quiz_id', 'question_id', 'title']);
                    },
                ]);
            }
        ])->first();


        return view('frontend.pages.quiz-submit', compact('quiz'));
    }

    public function quiz_submit()
    {
        // $this->middleware('api:auth');

        // $user = auth()->user();


        // $isAttemtQuiz = QuizQuestionSubmission::where('user_id', $user->id)->where('quiz_id', request()->quiz_id)->first();
        // if($isAttemtQuiz->quiz_id == request()->quiz_id) {
        //     return redirect()->route('website.quizes');
        // }



        if (isset(request()->quiz_id) && isset(request()->submission)) {

            $corrects = [];
            $total = 0;

            $entries = [];
            $quiz = Quiz::find(request()->quiz_id);
            foreach (request()->submission as $question_id => $submission_ids) {
                sort($submission_ids);
                $correct_answers = QuizQuestionOption::where('question_id', $question_id)->where('is_correct', 1)
                    ->get()->map(function ($i) {
                        return $i->id;
                    })->toArray();

                // dd(request()->correct_answers);

                $question = QuizQuestion::find($question_id);

                $corrects[] = [
                    $question_id => $submission_ids == $correct_answers,
                    "mark" => $question->mark,
                    "correct" => $correct_answers,
                    "given" => $submission_ids,
                ];

                foreach ($submission_ids as $option_id) {
                    $option = QuizQuestionOption::where('id', $option_id)->where('question_id', $question_id)->first();
                    $entries[] = [
                        "user_id" => auth()->user()->id,
                        'quiz_id' => $quiz->id,
                        'question_id' => $question->id,
                        'option_id' => (int) $option_id,
                        'is_correct' => $option->is_correct,
                    ];
                }

                // Safely accessing 'given' key
                // $last_correct = end($corrects);
                // $given = isset($last_correct['given']) ? $last_correct['given'] : null;

                // $lastIndex = count($corrects) - 1;
                // $given = $corrects[$lastIndex]['given'];

                // dd($submission_ids, $correct_answers, $corrects, $corrects[count($corrects) - 1]['correct'], $given);

                if ($submission_ids == $correct_answers) {
                    $total += $question->mark;
                }

                // $quiz_counts = $quiz->questions()->count();
            }
            // dd($entries);

            QuizQuestionSubmission::insert($entries);
            $quiz_user = new QuizUser();
            $quiz_user->user_id = auth()->user()->id;
            $quiz_user->quiz_id = $quiz->id;
            $quiz_user->mark = $total;
            $quiz_user->save();

            return redirect()->route('website.quizes.details',  ['id' => $quiz->id]);
        }

        return redirect()->back();
    }


    public function quiz_details($id)
    {
        $user_id = auth()->user()->id;
        $quiz_submissions = QuizQuestionSubmission::where('user_id', $user_id)->where('quiz_id', $id)->get();
        // return $quiz_submissions;

        $quiz = Quiz::where('id', $id)->with([
            'questions' => function ($q) {
                $q->with([
                    'options' => function ($q) {
                        $q->select(['id', 'quiz_id', 'question_id', 'title', 'is_correct']);
                    },
                ]);
            }
        ])->first();

        // return $quiz;

        $optionsByQuestion = [];
        foreach ($quiz_submissions as $quiz_submission) {
            $questionId = $quiz_submission['question_id'];
            if (!isset($optionsByQuestion[$questionId])) {
                $optionsByQuestion[$questionId] = [];
            }
            $optionsByQuestion[$questionId][] = $quiz_submission['option_id'];
        }

        foreach ($quiz['questions'] as &$question) {
            $questionId = $question['id'];
            $question['user_submissions'] = isset($optionsByQuestion[$questionId]) ? $optionsByQuestion[$questionId] : [];
        }

        unset($question);

        // return $quiz;

        return view('frontend.pages.quiz-submission-details', compact('quiz', 'quiz_submissions'));
    }
}



















// quizes {
//     $user = User::find(auth()->user()->id);
//         $userWithCourses = $user->load([
//             'batchStudents' => function ($query) {
//                 $query->select('course_id', 'id', 'batch_id', 'student_id', 'course_percent', 'is_complete');
//             },
//             'batchStudents.course' => function ($query) {
//                 $query->select('id', 'title', 'image', 'slug');
//             },
//             'batchStudents.batch' => function ($q2) {
//                 $q2->select('id', 'batch_name', 'class_days', 'class_start_time', 'class_end_time');
//             }
//         ]);

//         Use collection methods to split courses based on 'is_complete'
//         $completedCourses = $userWithCourses->batchStudents->where('is_complete', 'complete');
//         $incompleteCourses = $userWithCourses->batchStudents->where('is_complete', 'incomplete');
//         dd($userWithCourses, $completedCourses, $incompleteCourses);



//         return view('frontend.pages.quiz', [
//             'user_course' => $userWithCourses->batchStudents,
//             'complete_courses' => $completedCourses,
//             'incomplete_courses' => $incompleteCourses,
//         ]);
// }
