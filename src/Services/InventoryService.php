<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/6
 * Time: 3:08 PM
 */

namespace Chang\Erp\Services;


use App\Exceptions\InventoryOverflowException;
use Chang\Erp\Contracts\Expendable;
use Chang\Erp\Models\ExpendItem;
use Chang\Erp\Models\ExpendItems;
use Chang\Erp\Models\InventoryExpend;
use Chang\Erp\Models\Warehouse;
use Illuminate\Support\Facades\DB;

/**
 * Class InventoryService
 * @package Chang\Erp\Services
 */
class InventoryService
{
    /**
     * 产生出货记录
     * @param Expendable $expendable
     * @return mixed
     */
    public static function expend(Expendable $expendable)
    {
        return DB::transaction(function () use ($expendable) {
            $instance = new static();

            $instance->checkHasTake($expendable);

            return $instance->storeInventoryExpends(
                $expendable,
                $instance->checkInventoryOverflow($expendable)
            );
        });
    }

    /**
     * 取消出货记录
     * @param Expendable $expendable
     * @return mixed
     */
    public static function cancelExpend(Expendable $expendable)
    {
        return DB::transaction(function () use ($expendable) {
            return $expendable->cancelExpend();
        });
    }

    /**
     * 检测是否已出库，如何出货，就重置
     * @param Expendable $expendable
     */
    protected function checkHasTake(Expendable $expendable)
    {
        if ($expendable->inventoryExpends()->count() > 0) {
            $expendable->reExpend();
        }
    }

    /**
     * 储存出货记录
     * @param Expendable $expendable
     * @param ExpendItems $assignment
     * @return mixed
     */
    protected function storeInventoryExpends(Expendable $expendable, ExpendItems $assignment)
    {

        $description = $expendable->getDescription();
        // 创建或更新出库记录
        return $assignment->groupBy('warehouse_id')->map(function (ExpendItems $items, $key) use (
            $expendable,
            $description
        ) {

            /** @var InventoryExpend $inventoryExpend */
            $inventoryExpend = $expendable->inventoryExpend()->updateOrCreate([
                'expendable_type' => get_class($expendable),
                'expendable_id' => $expendable->id,
            ], [
                'description' => $description,
                'warehouse_id' => $key,
            ]);


            $items->each(function (ExpendItem $item) use ($inventoryExpend) {
                $inventoryExpend->items()->updateOrCreate(
                    [
                        'inventory_expend_id' => $inventoryExpend->id,
                        'product_variant_id' => $item->product_variant_id,
                    ],
                    $item->toArray()
                );
            });

            return tap($inventoryExpend, function (InventoryExpend $inventoryExpend) {
                $inventoryExpend->statusToConfirmed();
            });
        })->flatten();
    }


    /**
     * 分配出货仓库
     * @param Expendable $expendable
     * @return mixed
     */
    protected function assignmentWarehouse(Expendable $expendable)
    {
        // 查出所有变体数量 items
        return $expendable->getExpendItemList()->map(function ($item) {
            $item->warehouse_ids = Warehouse::findVariant($item->product_variant_id, $item->pcs)->pluck('warehouse_id');
            return $item;
        })->tap(function ($item) {
            $all = $item->pluck('warehouse_ids');
            $all->reduce(function ($res, $warehouses) use ($all) {
                $recommend = $warehouses->mapWithKeys(function ($warehouse) use ($all) {
                    return $all->reduce(function ($reg, $cur) use ($warehouse) {
                        return tap($reg, function (&$reg) use ($cur, $warehouse) {
                            if ($cur->contains($warehouse)) {
                                $reg[$warehouse] = $reg[$warehouse] + 1;
                            }
                        });
                    }, [$warehouse => 0]);
                });
                $res->push($recommend->sort()->flip()->last());
                return $res;
            }, collect([]))->tap(function ($recommend) use ($item) {
                $item->each(function ($item, $key) use ($recommend) {
                    $item->warehouse_id = $recommend[$key];
                });
            });
        });
    }

    /**
     * 检测库存溢出
     * @param Expendable $expendable
     * @return mixed
     */
    protected function checkInventoryOverflow(Expendable $expendable)
    {
        return tap($this->assignmentWarehouse($expendable), function ($assignment) {
            $assignment->filter(function ($item) {
                return is_null($item->warehouse_id);
            })->tap(function ($item) {
                if ($item->count() > 0) {
                    $message = '';
                    $item->pluck('product_variant_id')->each(function ($id) use (&$message) {
                        $message = $message . '变体id[' . $id . ']库存不足 ';
                    });
                    throw new InventoryOverflowException($message);
                }
            });
        });
    }
}