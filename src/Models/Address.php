<?php

namespace Chang\Erp\Models;


class Address extends Model
{
    protected $fillable = [
        'name',
        'tel',
        'phone',
        'fax',
        'zip',
        'country',
        'province',
        'city',
        'district',
        'address',
        'en'
    ];

    protected $casts = [
        'en'=>'array'
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}