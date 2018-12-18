<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Inventoriable;
use Chang\Erp\Events\InventoryPut;
use Chang\Erp\Events\InventoryTake;
use Chang\Erp\Scopes\SupplierInventoryScope;

class Inventory extends Model
{
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'product_variant_id',
        'stock',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SupplierInventoryScope());
    }


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
     * @param InventoryIncome $inventoryIncome
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function put(InventoryIncome $inventoryIncome)
    {
        return $inventoryIncome->items->map(function ($item) use ($inventoryIncome) {
            $inventoryItem = [
                'warehouse_id' => $inventoryIncome->warehouse_id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'stock' => $item->pcs,
            ];
            $inventory = static::findWarehouseVariants(
                $inventoryItem['warehouse_id'],
                $inventoryItem['product_variant_id']
            )->first();

            if ($inventory) {
                $inventory->increment('stock', $inventoryItem['stock']);
                return $inventory;
            }
            return static::create($inventoryItem);
        });
    }

    /**
     * 出库
     * @param InventoryExpend $inventoryExpend
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public static function take(InventoryExpend $inventoryExpend)
    {
        return $inventoryExpend->items->map(function ($item) use ($inventoryExpend) {
            return tap($inventoryExpend->warehouse->inventories()->where('product_variant_id',
                $item->product_variant_id)->first(),
                function ($inventory) use ($item) {
                    $inventory->decrement('stock', $item->pcs);
                });
        })->tap(function () use ($inventoryExpend) {
//            event(new InventoryTake($inventoryExpend));
        });
    }

    /**
     * 取消出货
     * @param InventoryExpend $inventoryExpend
     */
    public static function rollback(InventoryExpend $inventoryExpend)
    {
        $inventoryExpend->items->each(function ($item) use ($inventoryExpend) {
            tap($inventoryExpend->warehouse->inventories()->where('product_variant_id',
                $item->product_variant_id)->first(),
                function ($inventory) use ($item) {
                    $inventory->increment('stock', $item->pcs);
                });
        });
    }

    public function scopeBySupplier($query,Supplier $supplier)
    {
        return $query->whereIn('product_id',$supplier->productIds);
    }
}
