<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
        // Register morph map for polymorphic relations so saved types like
        // 'App\\Models\\Restaurant' resolve to the actual class names used
        // in the codebase.
        Relation::morphMap([
            'App\\Models\\Restaurant' => \App\Models\Restaurant\Restaurant::class,
            'App\\Models\\Place' => \App\Models\Place\Place::class,
            'App\\Models\\Table' => \App\Models\Table\Table::class,
        ]);
    }
}
