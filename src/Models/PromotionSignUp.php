<?php


namespace Chang\Erp\Models;


class PromotionSignUp extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = [
        'supplier_id',
        'promotion_id',
        'state',
    ];

    public function promotionVariants()
    {
        return $this->hasMany(PromotionVariant::class, 'sign_up_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}