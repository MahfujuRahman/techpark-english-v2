<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Modules\Management\SettingManagement\WebsiteSettings\Models\Model as SettingTitleValue;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        // WarehouseProductOut::observe(WarehouseProductOutObserver::class);

        View::composer('*', function ($view) {
            $app_settings = SettingTitleValue::with([
                'setting_values' => function ($query) {
                    $query->select('id', 'title', 'value', 'setting_title_id');
                }
            ])
                ->select('id', 'title')
                ->get()->toArray();

            $GLOBALS['app_settings'] = $app_settings;

            $view->with([
                'app_settings' => $app_settings,
            ]);
        });


        // config mail settings
        $emailConfig = DB::table('email_configures')->where('id', 1)->first();

        if ($emailConfig) {
            Config::set('mail.mailers.smtp.host', $emailConfig->host);
            Config::set('mail.mailers.smtp.port', $emailConfig->port);
            Config::set('mail.mailers.smtp.username', $emailConfig->username);
            Config::set('mail.mailers.smtp.password', $emailConfig->password);
            Config::set('mail.from.address', $emailConfig->mail_from_email);
            Config::set('mail.from.name', $emailConfig->mail_from_name);
        }

        // dd(config('mail')); 
    }
}
