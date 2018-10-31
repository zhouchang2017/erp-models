<?php

namespace Chang\Erp\Models;


class Video extends Model
{
    protected $connection = 'dealpaw';

    public $fillable = [
        'enabled',
        'cover',
        'description',
        'position',
        'product_id',
        'short_length',
        'short_size',
        'short_video',
        'size',
        'type',
        'user_id',
        'video',
    ];

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }


    /**
     * 目前一个视频只对应一款产品
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
