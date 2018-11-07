<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Inventoriable;
use Chang\Erp\Events\InventoryPut;
use Chang\Erp\Events\InventoryTake;

class Inventory extends Model
{
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'product_variant_id',
        'stock',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function supplier()
    {
        return $this->hasOne(SupplierVariant::class, 'product_variant_id', 'product_variant_id');
    }

    public function scopeFindWarehouseVariants($query, $warehouseId, $variantId, $productId = null)
    {
        return $query->where([
            ['warehouse_id', $warehouseId],
            ['product_variant_id', $variantId],
        ])->when($productId, function ($query, $productId) {
            return $query->where('product_id', $productId);
        });
    }

    /**
     * 入库
     * @param Inventoriable $inventoriable
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function put(Inventoriable $inventoriable)
    {
        return $inventoriable->items->map(function ($item) use ($inventoriable) {
            $inventoryItem = [
                'warehouse_id' => $inventoriable->toWarehouseId(),
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'stock' => $item->pcs,
            ];
            $inventory = static::findWarehouseVariants(
                $inventoryItem['warehouse_id'],
                $inventoryItem['product_variant_id']
            )->first();

            return $inventory ?
                $inventory->increment('stock', $inventoryItem['stock']) :
                static::create($inventoryItem);
        })->tap(function () use ($inventoriable) {
            event(new InventoryPut($inventoriable));
        });
    }

    /**
     * 出库
     * @param Inventoriable $inventoriable
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function take(Inventoriable $inventoriable)
    {
        return $inventoriable->items->map(function ($item) use ($inventoriable) {
            return tap($inventoriable->warehouse->inventories()->where('product_variant_id', 32)->first(),
                function ($inventory) use ($item) {
                    $inventory->decrement('stock', $item->pcs);
                });
        })->tap(function () use ($inventoriable) {
            event(new InventoryTake($inventoriable));
        });
    }
}
