<?php

namespace App\Providers;

use App\interfaces\CategoryInterface;
use App\Interfaces\TagInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CourseRepository;
use App\Interfaces\CourseInterface;

/**
 
 *@OA\Info(
 *title="E-Learning",
 *version="1.0.0"
 *)
 */

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //

        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(TagInterface::class, TagRepository::class);
        $this->app->bind(CourseInterface::class, CourseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
