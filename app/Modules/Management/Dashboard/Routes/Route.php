<?php

use  App\Modules\Management\Dashboard\Controller\Controller;
use App\Services\GoogleAnalyticsService;

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('auth:api')->group(function () {
    Route::get('get-all-dashboard-data', [Controller::class, 'GetAllDashboardData']);


    Route::get('/analytics', function (GoogleAnalyticsService $ga) {
        return response()->json([
            'metrics_over_time' => $ga->getMetricsOverTime(),
            'top_pages' => $ga->getTopPages(),
            'users_by_country' => $ga->getUsersByCountry(),
        ]);
    });
});
