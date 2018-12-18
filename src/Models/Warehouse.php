<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Addressable;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Traits\AddressableTrait;
use Chang\Erp\Traits\ExpendableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model implements Addressable, Expendable
{
    use AddressableTrait, ExpendableTrait;

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

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }


    public function scopeWhereVariant($query, $id, $stock = 1, $type = 1, $warehouseId = null)
    {
        return $query->where('type_id', $type)->when($warehouseId, function ($query, $warehouseId) {
            return $query->where('id', $warehouseId);
        })->with([
            'inventories' => function ($query) use ($id, $stock) {
                $query->where([
                    ['product_variant_id', $id],
                    ['stock', '>=', $stock],
                ]);
            },
        ]);
    }

    /**
     * 通过变体id获取哪个仓库存在
     * @param $id [变体id]
     * @param int $stock [库存数量]
     * @param int $type [仓库类型]
     * @param null $warehouseId [仓库id]
     * @return mixed
     */
    public static function findVariant($id, $stock = 1, $type = 1, $warehouseId = null)
    {
        return static::whereVariant($id, $stock, $type, $warehouseId)->get()->filter(function ($item) {
            return $item->inventories->count() > 0;
        })->flatMap(function ($item) {
            return $item->inventories;
        });
    }

    /**
     * 仓库总所有商品
     * @return BelongsToMany
     */
    public function variants()
    {
        $dbName = config('database.connections.' . config('database.default') . '.database');
        return $this->belongsToMany(
            ProductVariant::class,
            $dbName . '.inventories'
        )
            ->withPivot('product_id','stock');
    }

    public function getVariantsAttribute()
    {
        return $this->variants()->get();
    }

    public function getExpendItemList(): ExpendItems
    {
        // TODO: Implement getExpendItemList() method.
    }

}
