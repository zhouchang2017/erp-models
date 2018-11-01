<?php

namespace Chang\Erp;

use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Filesystem\Filesystem;

class ErpServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/erp.php', 'database.connections'
        );
        $this->app->bind(Filesystem::class, \Chang\Erp\Media\Filesystem\Filesystem::class);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }
}