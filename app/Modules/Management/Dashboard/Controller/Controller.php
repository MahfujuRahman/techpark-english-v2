<?php

namespace App\Modules\Management\Dashboard\Controller;

use App\Modules\Management\Dashboard\Actions\GetAllDashboardData;
use App\Modules\Management\Dashboard\Actions\GetEmployeeDashboardData;
use App\Services\GoogleAnalyticsService;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{
    protected $ga;

    public function __construct(GoogleAnalyticsService $ga)
    {
        $this->ga = $ga;
    }


    public function GetAllDashboardData()
    {
        // Get historical data (last 7 days)
        $metricsOverTime = $this->ga->getMetricsOverTime();
        $topPages = $this->ga->getTopPages();
        $usersByCountry = $this->ga->getUsersByCountry();

        // Get real-time data (current day/last 30 minutes)
        $realtimeActiveUsers = $this->ga->getRealTimeActiveUsers();
        $realtimePageviews = $this->ga->getRealtimePageviews();
        $realtimeTopPages = $this->ga->getRealtimeTopPages();

        $data = GetAllDashboardData::execute(); // now it's an array

        return entityResponse(array_merge($data, [
            'google_analytics' => [
                'historical' => [
                    'metrics_over_time' => $metricsOverTime,
                    'top_pages' => $topPages,
                    'users_by_country' => $usersByCountry,
                ],
                'realtime' => [
                    'active_users' => $realtimeActiveUsers,
                    'pageviews' => $realtimePageviews,
                    'top_pages' => $realtimeTopPages
                ]
            ]
        ]));
    }
}
