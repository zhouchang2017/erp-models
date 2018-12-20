<?php

namespace Chang\Erp\Models;


use Spatie\ModelStatus\HasStatuses;

class SupplierBill extends Model
{
    use HasStatuses;

    const UN_CONFIRMED = 'unconfirmed'; //未确认
    const CONFIRMED = 'confirmed';  //已确认
    const COMPLETED = 'completed'; //已提现

    protected $fillable = [
        'supplier_id',
        'price',
    ];

    public function billable()
    {
        return $this->morphTo();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /*
     * 账单初始化
     * */
    public function statusToUnConfirmed($reason = '账单初始化，未确认')
    {
        $this->setStatus(self::UN_CONFIRMED, $reason);
    }

    /*
     * 标记确认账单
     * */
    public function statusToConfirmed($reason = '确认账单')
    {
        $this->setStatus(self::CONFIRMED, $reason);
    }

    /*
     * 标记账单已提现
     * */
    public function statusToCompleted($reason = '账单提现')
    {
        if ($this->status === self::CONFIRMED) {
            $this->setStatus(self::COMPLETED, $reason);
        } else {
            throw new \Exception('账单未确认！');
        }
    }
}
