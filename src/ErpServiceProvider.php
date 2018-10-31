<?php

namespace Chang\Erp;

use Illuminate\Support\ServiceProvider;

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
    }
}