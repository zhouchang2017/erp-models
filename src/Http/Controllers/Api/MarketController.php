<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\Market;
use Chang\Erp\Services\OrderService;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{

    public function syncAll()
    {
        DB::transaction(function () {
            collect(Market::$marketables)->each(function ($marketable) {
                app($marketable)->all()->each->sync();
            });
        });
        return response()->json(['message' => '同步完成'], 201);
    }
}
