<?php

namespace Chang\Erp\Models;

use Chang\Erp\Support\NodeCollection;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;


class Taxon extends Model
{
    use Translatable, NodeTrait, SoftDeletes;

    protected $connection = 'dealpaw';

    public $translationForeignKey = 'translatable_id';

    public $translationModel = TaxonTranslation::class;

    public $translatedAttributes = ['name'];

    public function getLftName()
    {
        return 'tree_left';
    }

    public function getRgtName()
    {
        return 'tree_right';
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public function setParentAttribute($value)
    {
        $this->setParentIdAttribute($value);
    }


    protected $fillable = [
        'code',
        'parent_id',
        'position',
        'tree_root',
        'image',
        'tree_level',
        'tree_left',
        'tree_right',
    ];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

//        static::observe(TaxonObserver::class);
    }

    public function scopeChildren($query, $treeLeft, $treeRight)
    {
        return $query->where('tree_left', '>', $treeLeft)->where('tree_right', '<', $treeRight);
    }

    public function products()
    {
//        return $this->belongsToMany(Product::class, 'product_taxon');
    }

    public function productIds(): array
    {
//        $taxonIds = $this->descendants()->pluck('id');
//        $taxonIds->push($this->id);
//
//        $ids = DB::table('product_taxon')->whereIn('taxon_id', $taxonIds)->pluck('product_id')->toArray();
//        $ids = array_merge($ids, Product::whereIn('taxon_id', $taxonIds)->pluck('id')->toArray());
//
//        return $ids;
    }

    public function hasChildren()
    {
        return (bool)$this->children()->count();
    }

    public function getParentNameAttribute()
    {
        return $this->parent->name ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function newCollection(array $models = [])
    {
        return new NodeCollection($models);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productOptions()
    {
        return $this->hasMany(ProductOption::class);
    }
}
