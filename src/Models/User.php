<?php

namespace Chang\Erp\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

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
        return $this->hasMany(VariantProvider::class, 'user_id', 'product_variant_id');
    }

    public function provider()
    {
        return $this->hasOne(ProductProvider::class);
    }

//    public function productVariants()
//    {
//        return $this->belongsToMany(ProductVariant::class, 'variant_provider','')->withPivot('price')->withTimestamps();
//    }
}
