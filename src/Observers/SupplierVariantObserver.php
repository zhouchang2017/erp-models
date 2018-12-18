<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Models\SupplierVariant;

class SupplierVariantObserver
{
    public function created(SupplierVariant $supplierVariant)
    {
        // 冗余名称
        $supplierVariant->name = $supplierVariant->variant->getName();
        $supplierVariant->save();
    }
}