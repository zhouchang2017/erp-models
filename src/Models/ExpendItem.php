<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/9
 * Time: 4:24 PM
 */

namespace Chang\Erp\Models;

use Illuminate\Support\Collection;

/**
 * Class ExpendItem
 * @package Chang\Erp\Models
 */
class ExpendItem extends Collection
{

    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'pcs',
        'price',
    ];

    /**
     * ExpendItem constructor.
     * @param array $items
     * @throws \Exception
     */
    public function __construct($items = [])
    {
        if ( !array_has($items, $this->fillable)) {
            throw new \Exception('参数错误');
        }
        parent::__construct(array_only($items, $this->fillable));
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, $this->fillable)) {
            return $this->get($name);
        }
        parent::__get($name);
    }


    /**
     * 所属变体
     * @return ProductVariant
     */
    public function variants()
    {
        return ProductVariant::find($this->get('product_variant_id'));
    }


    /**
     * 所属产品
     * @return Product
     */
    public function product()
    {
        return Product::find($this->get('product_id'));
    }


    /**
     * 软锁定库存
     */
    public function lock()
    {
        $this->variants()->decrement('stock', $this->get('pcs'));
        activity('系统操作')
            ->performedOn($this->variants())
            ->log('锁定预出库存' . $this->get('pcs'));
    }

    public function revert()
    {
        $this->variants()->increment('stock', $this->get('pcs'));
        activity('系统操作')
            ->performedOn($this->variants())
            ->log('还原预出库库存' . $this->get('pcs'));
    }
}