<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\Product;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class ProductController extends Controller
{

    /**
     * 供应商提交审核
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function review(Product $product)
    {
        $product->statusToPadding();

        return response()->json([
            'status' => $product->getCheckStatus(),
            'title' => '提交成功',
            'message' => '请耐心等待平台审核',
            'type' => 'success',
        ], 201);
    }

    public function approved(Product $product)
    {
        $product->statusToApproved();

        return response()->json([
            'status' => $product->getCheckStatus(),
            'title' => '审核通过',
            'message' => '审核已通过，该产品已进入产品库',
            'type' => 'success',
        ], 201);
    }
}
