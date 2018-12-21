<?php
namespace Chang\Erp\Http\Controllers\Rpc;

use Chang\Erp\Models\User;

class TestRpcController
{
    public function user()
    {
        return User::find(1);
    }
}