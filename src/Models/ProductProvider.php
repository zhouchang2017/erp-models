<?php

namespace Chang\Erp\Models;

use Chang\Erp\Traits\AddressTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ProductProvider extends Model implements HasMedia
{
    use AddressTrait, HasMediaTrait;

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
        $this->addMediaCollection('product_provider_image');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
