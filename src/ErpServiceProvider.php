<?php

namespace Chang\Erp;

use Chang\Erp\Events\InventoryIncomeShipped;
use Chang\Erp\Listeners\InventoryIncomeStatusToShipped;
use Chang\Erp\Listeners\SendShipmentNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Filesystem\Filesystem;

class ErpServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        InventoryIncomeShipped::class => [
            InventoryIncomeStatusToShipped::class,
            SendShipmentNotification::class,
        ],
    ];


    public function boot()
    {
        $this->registerListeners();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/erp.php', 'database.connections'
        );
        $this->app->bind(Filesystem::class, \Chang\Erp\Media\Filesystem\Filesystem::class);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        return $this->listen;
    }

    protected function registerListeners()
    {
        foreach ($this->listens() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}