<?php

namespace Chang\Erp\Models;


class WarehouseType extends Model
{
    protected $fillable = ['name', 'description', 'enabled'];

    /**
     * 应该被转换成原生类型的属性。
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'type_id');
    }
}
