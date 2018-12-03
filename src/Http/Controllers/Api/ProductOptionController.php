<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\ProductAttribute;
use Chang\Erp\Models\ProductOption;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class ProductOptionController extends Controller
{

    public function index(Request $request)
    {
        return ProductOption::when($request->taxon, function ($query, $taxon) {
            $query->whereTaxon($taxon);
        })->withTranslation()->with('values')->get();
    }
}
