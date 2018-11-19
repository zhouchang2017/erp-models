<?php

namespace Chang\Erp\Models;


class Wechat extends Model
{
    protected $fillable = [
        'open_id',
        'avatar',
        'nickname',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
