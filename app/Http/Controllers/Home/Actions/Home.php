<?php

namespace App\Http\Controllers\Home\Actions;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Modules\Management\SeminerManagement\Seminer\Models\Model as Seminars;
use App\Modules\Management\WebsiteManagement\WebsiteBrand\Models\Model as Brand;
use App\Modules\Management\WebsiteManagement\SubBanner\Models\Model as SubBanner;
use App\Modules\Management\WebsiteManagement\WebsiteBanner\Models\Model as Banner;
use App\Modules\Management\WebsiteManagement\OurTrainer\Models\Model as OurTrainer;
use App\Modules\Management\CourseManagement\CourseBatch\Models\Model as CourseBatches;
use App\Modules\Management\WebsiteManagement\SuccssStories\Models\Model as SuccessStory;
use App\Modules\Management\WebsiteManagement\OurSpeciality\Models\Model as OurSpeciality;


class Home
{
    static $model = \App\Modules\Management\CommunicationManagement\ContactMessage\Models\Model::class;

    public static function execute($course)
    {
        $cacheKey = 'home_page_html_v1';
        // $ttlMinutes = 60; // cache time-to-live

        // if (Cache::has($cacheKey)) {
        //     $html = Cache::get($cacheKey);
        //     return response($html)->header('X-Cache-Status', 'HIT');
        // }

        $banners = Banner::where('is_featured', 1)->where('status', 1)->orderBy('id', 'desc')->get();
        $subBanners = SubBanner::where('status', 1)->orderBy('id', 'desc')->get();
        $our_speciality = OurSpeciality::where('status', 1)->orderBy('id', 'desc')->get();
        $success_stories = SuccessStory::where('status', 1)->limit(6)->orderBy('id', 'desc')->get();
        $our_trainers = OurTrainer::where('status', 1)->orderBy('id', 'desc')->first();
        $seminars = Seminars::where('date_time', '>', Carbon::today())->where('status', 'active')->get();
        $brands = Brand::where('status', 1)->get();
        $courseBatch = CourseBatches::active()->orderBy('id', 'DESC')->get();

        $courses = $course['courses'];
        $course_types = $course['course_types'];

        $data = [
            'banners' => $banners,
            'subBanners' => $subBanners,
            'our_speciality' => $our_speciality,
            'success_stories' => $success_stories,
            'our_trainers' => $our_trainers,
            "seminars" => $seminars,
            'brands' => $brands,
            'courseBatches' => $courseBatch,
            'courses' => $courses,
            'course_types' => $course_types,
        ];


        // $html = view('frontend.pages.home.home', $data)->render();
        // Cache::put($cacheKey, $html, now()->addMinutes($ttlMinutes));

        // return response($html)->header('X-Cache-Status', 'MISS');
        Cache::forget($cacheKey);

        return view('frontend.pages.home.home', $data);
    }
}
