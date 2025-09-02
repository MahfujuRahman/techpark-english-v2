<?php 
namespace App\Http\Gateways\SSLCommerz;

use Illuminate\Support\ServiceProvider;

class SSLCommerzServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sslcommerz.php' => config_path('sslcommerz.php')
        ], 'sslc-config');
    }
}