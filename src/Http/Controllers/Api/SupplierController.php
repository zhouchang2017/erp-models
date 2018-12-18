<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/13
 * Time: 下午5:37
 */

namespace Chang\Erp\Http\Controllers\Api;


use Chang\Erp\Http\Controllers\Controller;
use Chang\Erp\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function variants(Supplier $supplier,Request $request)
    {
        return $supplier->variants()->search($request->search)->latest()->paginate();
    }
}