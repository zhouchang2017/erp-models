<?php

namespace Chang\Erp\Models;

use Chang\Erp\Traits\AddressableTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Supplier extends Model implements HasMedia
{
    use AddressableTrait, HasMediaTrait;

    protected $connection = 'mysql';

    protected $fillable = [
        'code',
        'level',
        'description',
        'email',
        'qq',
        'wechat',
    ];

    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
//        self::observe(ProductProviderObserver::class);
    }

    public function registerMediaCollections()
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('supplier_image');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variants()
    {
        return $this->hasMany(SupplierVariant::class, 'user_id', 'user_id');
    }
}
