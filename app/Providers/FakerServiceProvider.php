<?php

namespace App\Providers;

use App\Faker\{RandomUserProvider, PicsumPhotoProvider};
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Support\ServiceProvider;

class FakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(FakerGenerator::class, function () {
            $faker = FakerFactory::create();

            // Swap the default image provider
            $faker->addProvider(new RandomUserProvider($faker));
            $faker->addProvider(new PicsumPhotoProvider($faker));

            return $faker;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
