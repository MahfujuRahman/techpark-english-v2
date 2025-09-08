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
        $metricsOverTime = $this->ga->getMetricsOverTime('2daysAgo', 'today');
        $topPages = $this->ga->getTopPages();
        $usersByCountry = $this->ga->getUsersByCountry();

        $data = GetAllDashboardData::execute(); // now it's an array

        $data['google_analytics'] = [
            'metrics_over_time' => $metricsOverTime,
            'top_pages' => $topPages,
            'users_by_country' => $usersByCountry,
        ];

        return entityResponse(array_merge($data, [
            'google_analytics' => [
                'metrics_over_time' => $metricsOverTime,
                'top_pages' => $topPages,
                'users_by_country' => $usersByCountry,
            ]
        ]));
    }
}
