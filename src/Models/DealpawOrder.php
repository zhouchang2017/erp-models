<?php

namespace Chang\Erp\Models;


class DealpawOrder extends Model
{
    protected $connection = 'dealpaw';

    protected $table = 'order';

    public function address()
    {
        return $this->belongsTo(DealpawAddress::class, 'address_id');
    }

    public function dealpaw()
    {
        return $this->belongsTo(Dealpaw::class, 'channel_id');
    }
}
