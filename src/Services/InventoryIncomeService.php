<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/11
 * Time: 上午10:53
 */

namespace Chang\Erp\Services;


use Chang\Erp\Events\InventoryIncomeApprovedEvent;
use Chang\Erp\Events\InventoryIncomeCancelEvent;
use Chang\Erp\Events\InventoryIncomeCompletedEvent;
use Chang\Erp\Events\InventoryIncomePartShippedEvent;
use Chang\Erp\Events\InventoryIncomePendingEvent;
use Chang\Erp\Events\InventoryIncomeShippedEvent;
use Chang\Erp\Models\InventoryIncome;
use Illuminate\Http\Request;

class InventoryIncomeService
{
    protected $model;

    public function __construct(InventoryIncome $income)
    {
        $this->model = $income;
    }

    public function model(InventoryIncome $income)
    {
        $this->model = $income;
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
            ->log('创建入库记录');
    }

    public function statusToPending()
    {
        $this->model->statusToPadding();
        event(new InventoryIncomePendingEvent($this->model));
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('入库记录提交申请');
    }

    public function statusToApproved()
    {
        $this->model->statusToApproved();
        $this->createItemUnits();
        event(new InventoryIncomeApprovedEvent($this->model));
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('入库记录审核通过');
    }

    public function statusToPartShipped()
    {
        if ($this->model->status === $this->model::PART_SHIPPED) {
            return;
        }
        $this->model->statusToPartShipped();
        event(new InventoryIncomePartShippedEvent($this->model));
    }

    public function statusToShipped()
    {
        if ($this->model->status === $this->model::SHIPPED) {
            return;
        }
        $this->model->statusToShipped();
        event(new InventoryIncomeShippedEvent($this->model));
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('入库记录发货完成');
    }

    public function statusToCompleted()
    {
        $this->model->statusToCompleted();
        event(new InventoryIncomeCompletedEvent($this->model));
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('入库已完成');
    }

    public function statusToCancel()
    {
        $this->model->statusToCancel();
        event(new InventoryIncomeCancelEvent($this->model));
        activity()
            ->causedBy(auth()->user())
            ->performedOn($this->model)
            ->log('入库取消');
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

    public function put()
    {
        if ($this->model->status === $this->model::COMPLETED) {
            throw new \Exception('请勿重复入库');
        }
        return tap(InventoryService::put($this->model),function(){
            $this->statusToCompleted();
        });
    }
}