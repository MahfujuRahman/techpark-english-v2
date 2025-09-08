<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;

$propertyId = '504062861'; // Your GA4 Property ID

try {
    $client = new BetaAnalyticsDataClient([
        'credentials' => __DIR__ . '/storage/app/google-analytics.json'
    ]);

    $response = $client->runReport([
        'property' => "properties/{$propertyId}",
        'dateRanges' => [
            ['startDate' => '7daysAgo', 'endDate' => 'today']
        ],
        'metrics' => [
            ['name' => 'activeUsers'],
            ['name' => 'sessions']
        ]
    ]);

    foreach ($response->getRows() as $row) {
        echo "Date: " . $row->getDimensionValues()[0]->getValue() . "\n";
        echo "Active Users: " . $row->getMetricValues()[0]->getValue() . "\n";
        echo "Sessions: " . $row->getMetricValues()[1]->getValue() . "\n";
        echo "--------------------------\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
