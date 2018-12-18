<?php

namespace Chang\Erp\Scopes;

use Chang\Erp\Models\SupplierUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SupplierProductVariantScope implements Scope
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->user() instanceof SupplierUser) {
            $builder->whereIn('product_variants.id', auth()->user()->supplier->variant_ids);
        }
    }
}