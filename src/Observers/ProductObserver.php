<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\Product;
use Chang\Erp\Models\SupplierUser;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    public function created(Product $product)
    {
        if (Auth::user() instanceof SupplierUser) {
            // 供应商创建，添加关联
            $product->supplier()->attach(Auth::user()->supplier);
        }
    }
}