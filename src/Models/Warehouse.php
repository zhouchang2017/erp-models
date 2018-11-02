<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\AddressableTrait;

class Warehouse extends Model
{
    use AddressableTrait;

    protected $fillable = ['name', 'type_id'];

    protected $fieldSearchable = [
        'id',
        'name',
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
