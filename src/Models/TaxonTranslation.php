<?php

namespace Chang\Erp\Models;


class TaxonTranslation extends Model
{
    protected $connection = 'dealpaw';

//    public $timestamps = false;

    protected $fillable = ['name', 'description', 'slug', 'locale_code', 'translatable_id'];

    protected static $updateFillable = ['name', 'description', 'slug'];

    public function taxon()
    {
        return $this->belongsTo(Taxon::class, 'translatable_id');
    }
}
