<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    protected $propertyId;
    protected $accessToken;

    public function __construct()
    {
        $this->propertyId = env('GA4_PROPERTY_ID');
        $this->accessToken = $this->getAccessToken();
        
        // Debug logging
        Log::info('GA4 Property ID: ' . $this->propertyId);
        Log::info('Access Token exists: ' . ($this->accessToken ? 'Yes' : 'No'));
    }

    /**
     * Get OAuth2 Access Token from Service Account JSON
     */
    protected function getAccessToken()
    {
        try {
            $jsonPath = storage_path('app/techpark-english-7f75be18246a.json');
            
            if (!file_exists($jsonPath)) {
                Log::error('Service account JSON file not found at: ' . $jsonPath);
                return null;
            }
            
            $json = json_decode(file_get_contents($jsonPath), true);

            if (!$json || !isset($json['client_email']) || !isset($json['private_key'])) {
                Log::error('Invalid service account JSON structure');
                return null;
            }

            $header = ['alg'=>'RS256','typ'=>'JWT'];
            $now = time();
            $claim = [
                'iss' => $json['client_email'],
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
            ];

            // Use firebase/php-jwt package to sign JWT
            $jwt = \Firebase\JWT\JWT::encode($claim, $json['private_key'], 'RS256', null, $header);

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            $data = $response->json();
            
            if (!$response->successful() || !isset($data['access_token'])) {
                Log::error('Failed to get access token: ' . $response->body());
                return null;
            }

            Log::info('Successfully obtained access token');
            return $data['access_token'];
        } catch (\Exception $e) {
            Log::error('Error getting access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Run a GA4 report
     */
    protected function runReport(array $body)
    {
        if (!$this->accessToken) {
            Log::error('No access token available for GA4 API request');
            return ['rows' => []];
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", $body);

            if (!$response->successful()) {
                Log::error('GA4 API request failed: ' . $response->body());
                return ['rows' => []];
            }

            $result = $response->json();
            Log::info('GA4 API Response: ', $result);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error running GA4 report: ' . $e->getMessage());
            return ['rows' => []];
        }
    }

    public function getMetricsOverTime($startDate = '7daysAgo', $endDate = 'today')
    {
        $body = [
            'dimensions' => [['name' => 'date']],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'sessions'],
                ['name' => 'screenPageViews'],
            ],
            'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
            'orderBys' => [['dimension' => ['dimensionName' => 'date'], 'desc' => false]]
        ];

        $response = $this->runReport($body);

        // If no data from GA4, return demo data for demo
        if (empty($response['rows'])) {
            Log::warning('No GA4 data available, returning demo data');
            return $this->getDemoMetricsData();
        }

        // Create a map of all dates in the range with zero values
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate) + 86399; // Include the full day
        $dateMap = [];

        // Ensure we include all days from start to end
        for ($timestamp = $startTimestamp; $timestamp <= $endTimestamp; $timestamp += 86400) {
            $dateKey = date('Y-m-d', $timestamp);
            $dateMap[$dateKey] = [
                'date' => $dateKey,
                'active_users' => 0,
                'sessions' => 0,
                'pageviews' => 0,
            ];
        }

        // Fill in actual data from GA4 response
        foreach ($response['rows'] ?? [] as $row) {
            $dateValue = $row['dimensionValues'][0]['value'];
            $formattedDate = date('Y-m-d', strtotime($dateValue));

            if (isset($dateMap[$formattedDate])) {
                $dateMap[$formattedDate] = [
                    'date' => $formattedDate,
                    'active_users' => intval($row['metricValues'][0]['value']),
                    'sessions' => intval($row['metricValues'][1]['value']),
                    'pageviews' => intval($row['metricValues'][2]['value']),
                ];
            }
        }

        // Convert map to array and sort by date
        $data = array_values($dateMap);
        usort($data, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        Log::info('Metrics over time data: ', $data);
        return $data;
    }

    public function getTopPages($limit = 5)
    {
        $body = [
            'dimensions' => [['name' => 'pagePath']],
            'metrics' => [['name' => 'screenPageViews']],
            'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
            'limit' => $limit,
            'orderBys' => [['metric' => ['metricName' => 'screenPageViews'], 'desc' => true]]
        ];

        $response = $this->runReport($body);

        // If no data from GA4, return dummy data for demo
        if (empty($response['rows'])) {
            Log::warning('No GA4 top pages data available, returning demo data');
            return $this->getDemoTopPagesData();
        }

        $data = [];
        foreach ($response['rows'] ?? [] as $row) {
            $data[] = [
                'page' => $row['dimensionValues'][0]['value'],
                'pageviews' => intval($row['metricValues'][0]['value']),
            ];
        }
        
        Log::info('Top pages data: ', $data);
        return $data;
    }

    public function getUsersByCountry($limit = 5)
    {
        $body = [
            'dimensions' => [['name' => 'country']],
            'metrics' => [['name' => 'activeUsers']],
            'dateRanges' => [['startDate' => '7daysAgo', 'endDate' => 'today']],
            'limit' => $limit,
            'orderBys' => [['metric' => ['metricName' => 'activeUsers'], 'desc' => true]]
        ];

        $response = $this->runReport($body);

        // If no data from GA4, return dummy data for demo
        if (empty($response['rows'])) {
            Log::warning('No GA4 users by country data available, returning demo data');
            return $this->getDemoCountryData();
        }

        $data = [];
        foreach ($response['rows'] ?? [] as $row) {
            $data[] = [
                'country' => $row['dimensionValues'][0]['value'],
                'active_users' => intval($row['metricValues'][0]['value']),
            ];
        }
        
        Log::info('Users by country data: ', $data);
        return $data;
    }

    /**
     * Return demo metrics data when GA4 is not available
     */
    private function getDemoMetricsData()
    {
        $dates = [];
        $baseDate = strtotime('-7 days');
        
        for ($i = 0; $i < 8; $i++) {
            $dates[] = [
                'date' => date('Y-m-d', $baseDate + ($i * 86400)),
                'active_users' => rand(10, 50),
                'sessions' => rand(15, 80),
                'pageviews' => rand(20, 120)
            ];
        }
        
        return $dates;
    }

    /**
     * Return demo top pages data when GA4 is not available
     */
    private function getDemoTopPagesData()
    {
        return [
            ['page' => '/home', 'pageviews' => 45],
            ['page' => '/about', 'pageviews' => 32],
            ['page' => '/courses', 'pageviews' => 28],
            ['page' => '/contact', 'pageviews' => 18],
            ['page' => '/gallery', 'pageviews' => 12]
        ];
    }

    /**
     * Return demo country data when GA4 is not available
     */
    private function getDemoCountryData()
    {
        return [
            ['country' => 'Bangladesh', 'active_users' => 25],
            ['country' => 'India', 'active_users' => 18],
            ['country' => 'United States', 'active_users' => 12],
            ['country' => 'United Kingdom', 'active_users' => 8],
            ['country' => 'Canada', 'active_users' => 5]
        ];
    }

    /**
     * Get real-time active users (last 30 minutes)
     */
    public function getRealTimeActiveUsers()
    {
        if (!$this->accessToken) {
            Log::error('No access token available for GA4 realtime API request');
            return 0;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runRealtimeReport", [
                    'metrics' => [
                        ['name' => 'activeUsers']
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('GA4 Realtime API request failed: ' . $response->body());
                return 0;
            }

            $result = $response->json();
            Log::info('GA4 Realtime API Response: ', $result);

            if (isset($result['rows'][0]['metricValues'][0]['value'])) {
                return intval($result['rows'][0]['metricValues'][0]['value']);
            }

            return 0;
        } catch (\Exception $e) {
            Log::error('Error running GA4 realtime report: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get real-time pageviews (last 30 minutes)
     */
    public function getRealtimePageviews()
    {
        if (!$this->accessToken) {
            Log::error('No access token available for GA4 realtime API request');
            return 0;
        }

        try {
            $response = Http::withToken($this->accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runRealtimeReport", [
                    'metrics' => [
                        ['name' => 'screenPageViews']
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('GA4 Realtime Pageviews API request failed: ' . $response->body());
                return 0;
            }

            $result = $response->json();
            Log::info('GA4 Realtime Pageviews API Response: ', $result);

            if (isset($result['rows'][0]['metricValues'][0]['value'])) {
                return intval($result['rows'][0]['metricValues'][0]['value']);
            }

            return 0;
        } catch (\Exception $e) {
            Log::error('Error running GA4 realtime pageviews report: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get real-time top pages/locations (last 30 minutes)
     * Note: Real-time API has very limited dimensions available
     */
    public function getRealtimeTopPages($limit = 10)
    {
        if (!$this->accessToken) {
            Log::error('No access token available for GA4 realtime API request');
            return [];
        }

        try {
            // Real-time API has very limited dimensions. Let's try 'city' as suggested by the error
            $response = Http::withToken($this->accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runRealtimeReport", [
                    'dimensions' => [
                        ['name' => 'city']
                    ],
                    'metrics' => [
                        ['name' => 'activeUsers'],
                        ['name' => 'screenPageViews']
                    ],
                    'orderBys' => [
                        ['metric' => ['metricName' => 'activeUsers'], 'desc' => true]
                    ],
                    'limit' => $limit
                ]);

            if (!$response->successful()) {
                Log::error('GA4 Realtime Top Pages API request failed: ' . $response->body());
                // Return empty array instead of failing - real-time top pages is not critical
                return [];
            }

            $result = $response->json();
            Log::info('GA4 Realtime Top Cities API Response: ', $result);

            $data = [];
            if (isset($result['rows'])) {
                foreach ($result['rows'] as $row) {
                    $data[] = [
                        'location' => $row['dimensionValues'][0]['value'] ?: 'Unknown Location',
                        'active_users' => intval($row['metricValues'][0]['value']),
                        'pageviews' => intval($row['metricValues'][1]['value'])
                    ];
                }
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Error running GA4 realtime top cities report: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Test if the service account has proper access
     */
    public function testAccess()
    {
        if (!$this->accessToken) {
            return [
                'status' => 'error',
                'message' => 'No access token available'
            ];
        }

        // Try a simple metadata request first
        try {
            $response = Http::withToken($this->accessToken)
                ->get("https://analyticsadmin.googleapis.com/v1beta/properties/{$this->propertyId}");

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'Service account has access to the property',
                    'property_info' => $response->json()
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Permission denied: ' . $response->body(),
                    'suggestion' => 'Add analytics-service@techpark-english.iam.gserviceaccount.com to your GA4 property with Viewer permissions'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error testing access: ' . $e->getMessage()
            ];
        }
    }
}
