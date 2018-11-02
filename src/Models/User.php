<?php

namespace Chang\Erp\Models;

use Chang\Erp\Contracts\Incomeable;
use Chang\Erp\Traits\IncomeableTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Incomeable
{
    use Notifiable, HasRoles, IncomeableTrait;

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

    protected $casts = [
        'is_provider' => 'boolean',
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

    public function variants()
    {
        return $this->hasMany(SupplierVariant::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

//    public function productVariants()
//    {
//        return $this->belongsToMany(ProductVariant::class, 'variant_provider','')->withPivot('price')->withTimestamps();
//    }
}
