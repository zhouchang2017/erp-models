<?php

namespace Chang\Erp;

use Chang\Erp\Events\InventoryPut;
use Chang\Erp\Events\Shipped;
use Chang\Erp\Listeners\ChangeStatusToCompleted;
use Chang\Erp\Listeners\ChangeStatusToShipped;
use Chang\Erp\Listeners\SendShipmentNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;

class ErpServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Shipped::class => [
            ChangeStatusToShipped::class,
            SendShipmentNotification::class,
        ],
        InventoryPut::class => [
            ChangeStatusToCompleted::class,
        ],
    ];


    public function boot()
    {
        $this->registerListeners();
        $this->registerRoutes();
        $this->registerNovaConfig();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/database.php', 'database.connections'
        );
        $this->app->bind(Filesystem::class, \Chang\Erp\Media\Filesystem\Filesystem::class);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');
    }

    protected function registerNovaConfig()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::provideToScript([
                'erp-prefix' => '/erp-api',
            ]);
        });
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'Chang\Erp\Http\Controllers\Api',
            'domain' => config('nova.domain', null),
            'as' => 'erp.api.',
            'prefix' => 'erp-api',
            'middleware' => 'nova',
        ];
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