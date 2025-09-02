<?php

namespace App\Modules\Management\PaymentGateways\Seeder;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="App\Modules\Management\PaymentGateways\Seeder\Seeder"
     */
    static $model = \App\Modules\Management\PaymentGateways\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([
                'provider_name' => $faker->randomElement(array(
                    0 => 'bkash',
                    1 => 'nagad',
                    2 => 'sslcommerze',
                )),
                'api_key' => $faker->text(255),
                'secret_key' => $faker->text(255),
                'username' => $faker->text(255),
                'password' => $faker->text(255),
                'live' => $faker->boolean,
            ]);
        }
    }
}
