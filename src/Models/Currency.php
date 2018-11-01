<?php

namespace Chang\Erp\Models;

class Currency extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = ['code'];
}
