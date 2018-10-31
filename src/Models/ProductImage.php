<?php

namespace Chang\Erp\Models;

use Spatie\MediaLibrary\Models\Concerns\IsSorted;

class ProductImage extends Model
{
    use IsSorted;

    protected $connection = 'dealpaw';

    public $fillable = ['product_id', 'image', 'position'];

    public $sortable = [
        'order_column_name' => 'position',
    ];

    public function getFullUrl($field = 'image')
    {
        return $this->{$field} ?? null;
    }

    public function getThumbUrl($field = 'image')
    {
        return $this->getFullUrl($field) ? $this->getFullUrl($field) . '?imageView2/1/w/80/h/80/interlace/1/q/100' : null;
    }
}
