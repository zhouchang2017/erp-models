<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\ProductAttribute;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class ProductAttributeController extends Controller
{

    public function index(Request $request)
    {
        return ProductAttribute::when($request->taxon, function ($query, $taxon) {
            $query->whereTaxon($taxon);
        })->withTranslation()->get();
    }
}
