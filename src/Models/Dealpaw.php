<?php

namespace Chang\Erp\Models;


use Chang\Erp\Contracts\Marketable;
use Chang\Erp\Traits\MarketableTrait;

class Dealpaw extends Model implements Marketable
{
    use MarketableTrait;

    protected $connection = 'dealpaw';

    protected $table = 'channels';

    protected $fillable = ['code', 'enabled', 'locale_code', 'currency_code', 'name', 'description', 'email'];

    public function locales()
    {
        return $this->belongsToMany(Locale::class, 'channel_locale', 'channel_id', 'locale_id');
    }

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'channel_currency', 'channel_id', 'currency_id');
    }
}
