<?php

namespace App\Providers;

use App\Http\Usecase\Category\CategoryUsecase;
use App\Http\Usecase\Category\CategoryUsecaseInterface;
use App\Http\Usecase\Product\ProductUsecase;
use App\Http\Usecase\Product\ProductUsecaseInterface;
use Illuminate\Support\ServiceProvider;

class UsecaseProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryUsecaseInterface::class, CategoryUsecase::class);
        $this->app->bind(ProductUsecaseInterface::class, ProductUsecase::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
