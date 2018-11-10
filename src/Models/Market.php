<?php

namespace Chang\Erp\Models;

class Market extends Model
{
    protected $fillable = [
        'name',
        'description',
        'enabled',
    ];

    public static $marketables = [
        Dealpaw::class,
    ];

    public function marketable()
    {
        return $this->morphTo();
    }
}
