<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/15
 * Time: 下午1:33
 */

namespace Chang\Erp\Http\Controllers\Api;


use Chang\Erp\Http\Controllers\Controller;
use Chang\Erp\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        return Promotion::when($request->type,function($query,$type){
            $query->whereType($type);
        })->latest()->paginate(15);
    }

    public function show(Promotion $promotion)
    {
        return $promotion;
    }

    public function types()
    {
        return array_merge(
            ['all'=>'全部活动'],
            Promotion::typeMaps()
        );
    }
}