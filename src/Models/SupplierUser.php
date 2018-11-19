<?php

namespace Chang\Erp\Models;

use Chang\Erp\Traits\WechatableTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SupplierUser extends Authenticatable
{
    use Notifiable, WechatableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
