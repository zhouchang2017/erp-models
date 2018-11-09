<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/9
 * Time: 4:24 PM
 */

namespace Chang\Erp\Models;

use Illuminate\Support\Collection;

class ExpendItem extends Collection
{

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'pcs',
        'price',
    ];

    public function __construct($items = [])
    {
        if ( !array_has($items, $this->fillable)) {
            throw new \Exception('参数错误');
        }
        parent::__construct(array_only($items, $this->fillable));
    }

    public function __get($name)
    {
        if(in_array($name,$this->fillable)){
            return $this->get($name);
        }
        parent::__get($name);
    }
}