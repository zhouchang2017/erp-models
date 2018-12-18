<?php

namespace Chang\Erp;

use Chang\Erp\Events\CompletedEvent;
use Chang\Erp\Events\InventoryIncrementEvent;
use Chang\Erp\Events\InventoryPut;
use Chang\Erp\Events\InventoryTake;
use Chang\Erp\Events\ShippedEvent;
use Chang\Erp\Listeners\ChangeStatusToCompletedListener;
use Chang\Erp\Listeners\ChangeStatusToShippedListener;
use Chang\Erp\Listeners\ProductVariantStockListener;
use Chang\Erp\Listeners\SendCompletedNotificationListener;
use Chang\Erp\Listeners\SendShipmentNotificationListener;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\InventoryIncome;
use Illuminate\Http\Resources\Json\Resource;
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
        ShippedEvent::class => [
            ChangeStatusToShippedListener::class,
            SendShipmentNotificationListener::class,
        ],
        CompletedEvent::class => [
            ChangeStatusToCompletedListener::class,
            SendCompletedNotificationListener::class,
        ],
        InventoryIncrementEvent::class=>[
            ProductVariantStockListener::class
        ]
    ];


    public function boot()
    {
        $this->registerListeners();
        $this->registerRoutes();
        $this->registerNovaConfig();
        Route::model('inventoryIncome',InventoryIncome::class);
        Route::model('inventoryExpend',InventoryExpend::class);
        Resource::withoutWrapping();
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

                'locales'=>array_map(function ($value) {
                    return __($value);
                }, config('translatable.locales')),

                'indexLocale' => app()->getLocale(),
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