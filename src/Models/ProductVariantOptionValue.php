<?php

namespace Chang\Erp\Models;


use Dimsav\Translatable\Translatable;

class ProductVariantOptionValue extends Model
{
    use Translatable;

    protected $connection = 'dealpaw';

    protected $fillable = ['option_id', 'variant_id', 'value_id'];

    public $translationForeignKey = 'translatable_id';

    public $translationModel = ProductVariantOptionValueTranslation::class;

    public $translatedAttributes = ['value'];

    protected $with = ['selected'];

    protected $appends = ['unique_code'];

    public function getUniqueCodeAttribute()
    {
        return $this->selected ? $this->selected->unique_code : md5($this->value);
    }

    public function getNameAttribute()
    {
        return $this->selected ? $this->selected->value : $this->value;
    }

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'option_id');
    }

    public function selected()
    {
        return $this->belongsTo(ProductOptionValue::class, 'value_id');
    }


}
