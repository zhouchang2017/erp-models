<?php

namespace Chang\Erp\Models;


/**
 * @property SupplierUser user
 */
class Wechat extends Model
{
    protected $fillable = [
        'openid',
        'avatar',
        'nickname',
    ];

    public function user()
    {
        return $this->morphTo();
    }
}
