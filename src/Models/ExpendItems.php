<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/9
 * Time: 4:24 PM
 */

namespace Chang\Erp\Models;

use Illuminate\Support\Collection;

class ExpendItems extends Collection
{

    public function __construct($items = [])
    {
        if ($items instanceof Collection) {
            $items = $items->reduce(function ($res, $item) {
                return tap($res,function($res) use($item){
                    $res->push(new ExpendItem($item));
                });
            },collect([]));
        }
        parent::__construct($items);
    }
}