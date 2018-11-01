<?php

namespace Chang\Erp\Models;

class Market extends Model
{
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    public function marketable()
    {
        return $this->morphTo();
    }
}
