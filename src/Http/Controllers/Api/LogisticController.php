<?php

namespace Chang\Erp\Http\Controllers\Api;

use Chang\Erp\Models\Logistic;
use Illuminate\Http\Request;
use Chang\Erp\Http\Controllers\Controller;

class LogisticController extends Controller
{
    public function index()
    {
        return Logistic::all(['id', 'code', 'name']);
    }
}
