<?php

namespace Chang\Erp\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    protected $connection = 'mysql';

    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = $value ?? 0;
    }

}