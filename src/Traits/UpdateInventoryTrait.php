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

    /**
     * 更新审核时间
     */
    public function updateConfirmedAt()
    {
        if (is_null($this->confirmed_at)) {
            $this->beforeConfirmed();
            $this->confirmed_at = now();
            $this->afterConfirmed();
        }
    }

    /*
     * 手动标记审核操作,模型自动保存
     * */
    public function statusToConfirmed()
    {
        if ((int)$this->attributes['status'] < self::UN_SHIP) {
            $this->attributes['status'] = self::UN_SHIP;
            $this->updateConfirmedAt();
            $this->save();
        }
        return $this;
    }

    // 审核前置钩子
    protected function beforeConfirmed()
    {

    }

    // 审核后置钩子
    protected function afterConfirmed()
    {

    }

    /*
     * 出货单取消,模型自动保存
     * */
    public function statusToCancel()
    {
        if ((int)$this->attributes['status'] !== self::CANCEL) {
            $this->beforeCancel();
            $this->attributes['status'] = self::CANCEL;
            $this->save();
            $this->afterCancel();
        }
        return $this;
    }

    // 取消前置钩子
    protected function beforeCancel()
    {

    }

    // 取消后置钩子
    protected function afterCancel()
    {

    }

    public function statusToSave()
    {
        $this->attributes['status'] = self::UN_COMMIT;
        $this->confirmed_at = null;
        $this->save();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function statusToPadding()
    {
        $this->attributes['status'] = self::PADDING;
        $this->save();
    }

}