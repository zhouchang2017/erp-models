<?php

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Address;

trait AddressableTrait
{

    /**
     * @return string
     */
    public function getAddressMethod(): string
    {
        return 'addresses';
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function address()
    {
        return $this->morphOne(Address::class,'addressable');
    }


}