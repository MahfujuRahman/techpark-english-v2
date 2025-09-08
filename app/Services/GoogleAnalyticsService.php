<?php
namespace App\Services;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

class GoogleAnalyticsService
{
    protected $client;
    protected $propertyId;

    public function __construct()
    {
        $this->propertyId = env('GA4_PROPERTY_ID');
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => storage_path('app/google-analytics.json')
        ]);
    }

    public function getMetricsOverTime($startDate = '7daysAgo', $endDate = 'today')
    {
        $response = $this->client->runReport([
            'property' => "properties/{$this->propertyId}",
            'dimensions' => [['name' => 'date']],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'sessions'],
                ['name' => 'screenPageViews'],
            ],
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
        ]);

        $data = [];
        foreach ($response->getRows() as $row) {
            $data[] = [
                'date' => $row->getDimensionValues()[0]->getValue(),
                'active_users' => $row->getMetricValues()[0]->getValue(),
                'sessions' => $row->getMetricValues()[1]->getValue(),
                'pageviews' => $row->getMetricValues()[2]->getValue(),
            ];
        }
        return $data;
    }

    public function getTopPages($limit = 5)
    {
        $response = $this->client->runReport([
            'property' => "properties/{$this->propertyId}",
            'dimensions' => [['name' => 'pagePath']],
            'metrics' => [['name' => 'screenPageViews']],
            'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
            'limit' => $limit
        ]);

        $data = [];
        foreach ($response->getRows() as $row) {
            $data[] = [
                'page' => $row->getDimensionValues()[0]->getValue(),
                'pageviews' => $row->getMetricValues()[0]->getValue(),
            ];
        }
        return $data;
    }

    public function getUsersByCountry($limit = 5)
    {
        $response = $this->client->runReport([
            'property' => "properties/{$this->propertyId}",
            'dimensions' => [['name' => 'country']],
            'metrics' => [['name' => 'activeUsers']],
            'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
            'limit' => $limit
        ]);

        $data = [];
        foreach ($response->getRows() as $row) {
            $data[] = [
                'country' => $row->getDimensionValues()[0]->getValue(),
                'active_users' => $row->getMetricValues()[0]->getValue(),
            ];
        }
        return $data;
    }
}
