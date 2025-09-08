<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\About\Actions\About;
use App\Services\GoogleAnalyticsService;

class AboutController extends Controller
{

    protected $ga;

    public function __construct(GoogleAnalyticsService $ga)
    {
        $this->ga = $ga;
    }
    public function index()
    {
        $data = About::execute();
        return $data;
    }
}
