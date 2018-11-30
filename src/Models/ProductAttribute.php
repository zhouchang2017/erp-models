<?php

namespace Chang\Erp\Models;

use Dimsav\Translatable\Translatable;

class ProductAttribute extends Model
{
    use Translatable;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductAttributeTranslation::class;

    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'taxon_id', 'type', 'storage_type', 'configuration', 'position'];

    // type => ['text','textarea','checkbox','integer','percent','datetime','date','select']

    // storage_type => ['text','boolean','integer','float','datetime','date','json']

    public function taxon()
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'taxon_attribute',
            'attribute_id',
            'taxon_id');
    }
}
