<?php

namespace Chang\Erp\Models;

class DealpawAddress extends Model
{
    protected $connection = 'dealpaw';

    protected $table = 'addresses';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'postcode',
        'country_code',
        'province_code',
        'province_name',
        'city',
        'street',
        'user_id',
    ];
    public static $updateFillable = [
        'first_name',
        'last_name',
        'phone_number',
        'postcode',
        'country_code',
        'province_code',
        'province_name',
        'city',
        'street',
    ];

    public function order()
    {
        return $this->hasOne(DealpawOrder::class, 'address_id');
    }

    public function dealpaw()
    {
        return $this->hasOne(Dealpaw::class, 'channel_id');
    }

    public function simple()
    {
        return $this->country_code . ' ' . $this->province_name . ' ' . $this->city . ' ' . $this->street . ' '
            . ' 收件人:' . $this->first_name . ' ' . $this->last_name . ' 手机:' . $this->phone_number . ' 邮编:' . $this->postcode;
    }
}
