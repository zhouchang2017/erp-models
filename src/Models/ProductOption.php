<?php

namespace Chang\Erp\Models;

use Dimsav\Translatable\Translatable;

class ProductOption extends Model
{
    use Translatable;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductOptionTranslation::class;

    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'position'];

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class, 'option_id');
    }
}
