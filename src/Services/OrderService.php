<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/6
 * Time: 3:08 PM
 */

namespace Chang\Erp\Services;


use App\Exceptions\InventoryOverflowException;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Models\ExpendItem;
use Chang\Erp\Models\ExpendItems;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\Order;
use Chang\Erp\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function syncAll()
    {
        return collect(Order::$orderables)->mapWithKeys(function ($orderable) {
            return app($orderable)->syncAll();
        });
    }
}