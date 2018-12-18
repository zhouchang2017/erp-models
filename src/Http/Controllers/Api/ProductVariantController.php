<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/17
 * Time: 3:59 PM
 */

namespace Chang\Erp\Http\Controllers\Api;


use Chang\Erp\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController
{
    public function index(Request $request)
    {
        return ProductVariant::search($request->search)->latest()->paginate();
    }
}