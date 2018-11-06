<?php

namespace Chang\Erp\Models;


use Chang\Erp\Events\InventoryPut;

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

    public function scopeFindWarehouseVariants($query, $warehouseId, $variantId, $productId = null)
    {
        return $query->where([
            ['warehouse_id', $warehouseId],
            ['product_variant_id', $variantId],
        ])->when($productId, function ($query, $productId) {
            return $query->where('product_id', $productId);
        });
    }

    public static function put(InventoryIncome $income)
    {
        return $income->items->map(function ($item) use ($income) {
            $inventoryItem = [
                'warehouse_id' => $income->warehouse_id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'stock' => $item->pcs,
            ];
            $stock = static::findWarehouseVariants(
                $inventoryItem['warehouse_id'],
                $inventoryItem['product_variant_id']
            )->first();

            return $stock ?
                $stock->increment('stock', $inventoryItem['stock']) :
                static::create($inventoryItem);
        })->tap(function($inventory) use ($income){
            event(new InventoryPut($income));
        });
    }
}
