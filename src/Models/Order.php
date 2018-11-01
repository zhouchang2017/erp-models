<?php

namespace Chang\Erp\Models;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_status',
        'channel_id',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function orderable()
    {
        return $this->morphTo();
    }
}
