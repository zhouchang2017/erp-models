<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/14
 * Time: 上午10:29
 */

namespace Chang\Erp\Services;


use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Events\InventoryExpendApprovedEvent;
use Chang\Erp\Events\InventoryExpendCancelEvent;
use Chang\Erp\Events\InventoryExpendCompletedEvent;
use Chang\Erp\Events\InventoryExpendPartShippedEvent;
use Chang\Erp\Events\InventoryExpendPendingEvent;
use Chang\Erp\Events\InventoryExpendShippedEvent;
use Chang\Erp\Models\ExpendItem;
use Chang\Erp\Models\ExpendItems;
use Chang\Erp\Models\InventoryExpend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryExpendService
{
    protected $model;

    public function __construct(InventoryExpend $expend = null)
    {
        $this->model = $expend;
    }

    public function model(InventoryExpend $expend)
    {
        $this->model = $expend;
        return $this;
    }

    private function createItemUnits()
    {
        return $this->model->items->map->createItemUnits();
    }

    public function statusToSaved()
    {
        $this->model->statusToSave();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('退仓申请，已创建');
    }

    public function statusToPending()
    {
        $this->model->statusToPadding();
        InventoryService::take($this->model);
        $this->createItemUnits();
        // 锁库存！
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('提交审核,锁定库存');
        event(new InventoryExpendPendingEvent($this->model));
    }

    public function statusToApproved($reason = '同意出库,等待发货')
    {
        $this->model->statusToApproved($reason);
        $this->createItemUnits();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log($reason);
        event(new InventoryExpendApprovedEvent($this->model));
    }

    public function statusToPartShipped()
    {
        if ($this->model->status === $this->model::PART_SHIPPED) {
            return;
        }
        $this->model->statusToPartShipped();
        event(new InventoryExpendPartShippedEvent($this->model));
    }

    public function statusToShipped()
    {
        if ($this->model->status === $this->model::SHIPPED) {
            return;
        }
        $this->model->statusToShipped();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('退仓发货完成');
        event(new InventoryExpendShippedEvent($this->model));
    }

    public function statusToCompleted()
    {
        $this->model->statusToCompleted();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('退仓完成');
        event(new InventoryExpendCompletedEvent($this->model));
    }

    public function statusToCancel()
    {
        $this->model->statusToCancel();
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('取消退仓，归还库存');
        event(new InventoryExpendCancelEvent($this->model));
    }

    public function shipment(Request $request)
    {
        return (new ShipmentService($this->model))->trackableShipment($request)->tap(function(){
            tap($this->model->units->every->shipment,function($flag){
                // 检测发货状态
                // 部分发货
                //全部发货
                $flag ? $this->statusToShipped() : $this->statusToPartShipped();
            });
        });
    }

    public function createAttachment(Request $request)
    {

    }

    /**
     * 创建/更新出货记录,适用于订单出库
     * @param Expendable $expendable
     * @return mixed
     */
    public static function create(Expendable $expendable)
    {
        return DB::transaction(function ()use($expendable){
            $description = $expendable->getDescription();
            // 创建或更新出库记录
            return $expendable->getExpendItemList()->groupBy('warehouse_id')->map(function (ExpendItems $items, $key) use (
                $expendable,
                $description
            ) {

                /** @var InventoryExpend $inventoryExpend */
                $inventoryExpend = $expendable->inventoryExpend()->updateOrCreate([
                    'expendable_type' => get_class($expendable),
                    'expendable_id' => $expendable->id,
                ], [
                    'description' => $description,
                    'warehouse_id' => $key,
                ]);


                $items->each(function (ExpendItem $item) use ($inventoryExpend) {
                    $inventoryExpend->items()->updateOrCreate(
                        [
                            'inventory_expend_id' => $inventoryExpend->id,
                            'product_variant_id' => $item->product_variant_id,
                        ],
                        $item->toArray()
                    );
                });

                return tap($inventoryExpend, function (InventoryExpend $inventoryExpend) {
                    (new static($inventoryExpend))->statusToApproved('订单，系统自动审核');
                });
            })->flatten();
        });
    }


    public function take()
    {
        if ($this->model->status === $this->model::COMPLETED) {
            throw new \Exception('请勿重复出库');
        }
        $this->statusToCompleted();
    }

    public function cancel()
    {
        if ($this->model->status === $this->model::CANCEL) {
            throw new \Exception('请勿重复取消');
        }
        // 返回库存
        InventoryService::rollback($this->model);
        $this->statusToCancel();
    }
}