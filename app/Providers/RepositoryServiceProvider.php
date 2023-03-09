<?php

namespace App\Providers;

use App\Repository\CategoryRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use App\Services\CategoryService;
use App\Services\ProductCategoryService;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepository::class, CategoryService::class);
        $this->app->bind(ProductRepository::class, ProductService::class);
        $this->app->bind(ProductCategoryRepository::class, ProductCategoryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
