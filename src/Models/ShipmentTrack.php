<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\MoneyFormatableTrait;

class ShipmentTrack extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [
        'logistic_id',
        'tracking_number',
        'price',
    ];
}
