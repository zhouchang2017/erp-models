<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Addressable;
use Chang\Erp\Traits\AddressableTrait;

class Warehouse extends Model implements Addressable
{
    use AddressableTrait;

    protected $fillable = ['name', 'type_id'];

    protected $fieldSearchable = [
        'id',
        'name',
        'enabled',
        'has_verify',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'has_verify' => 'boolean',
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

//        self::observe(WarehouseObserver::class);
    }


    public function type()
    {
        return $this->belongsTo(WarehouseType::class, 'type_id');
    }

}
