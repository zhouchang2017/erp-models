<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\ProductVariant;
use Chang\Erp\Models\SupplierUser;
use Illuminate\Support\Facades\Auth;

class ProductVariantObserver
{
    public function created(ProductVariant $variant)
    {
        if (Auth::user() instanceof SupplierUser) {
            // 供应商创建，添加关联
            Auth::user()->supplier->variants()->attach($variant);
        }
    }
}