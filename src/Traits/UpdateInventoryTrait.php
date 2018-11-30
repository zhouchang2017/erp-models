<?php

/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 6:02 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Warehouse;

trait UpdateInventoryTrait
{
    /**
     * 计算数量
     * @return mixed
     */
    public function calcTotalPcs()
    {
        return $this->items()->sum('pcs');
    }

    /**
     * 计算价格
     * @return mixed
     */
    public function calcTotalPrice()
    {
        return $this->items()->sum('price');
    }

    /**
     * 计算items 价格|数量
     */
    public function autoCalcItems()
    {
        $this->price = $this->calcTotalPrice();
        $this->pcs = $this->calcTotalPcs();
        return $this;
    }


    /*
     * 标记审核操作
     * */
    public function statusToConfirmed()
    {
        $this->setStatus(self::UN_SHIP, '等待发货');
    }


    /*
     * 进/出货单取消
     * */
    public function statusToCancel()
    {
        $this->setStatus(self::CANCEL, '取消');
    }

    /*
     * 标记提交审核
     * */
    public function statusToPadding()
    {
        $this->setStatus(self::PADDING, '待审核');
    }


    /*
     * 状态初始化
     * */
    public function statusToSave()
    {
        $this->setStatus(self::UN_COMMIT, '保存/未提交');
    }

    /*
     * 标记状态已发货
     * */
    public function statusToShipped()
    {
        $this->setStatus(self::SHIPPED, '已发货');
    }

    /*
     * 标记状态已完成
     * */
    public function statusToCompleted()
    {
        $this->setStatus(self::COMPLETED, '已完成');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}