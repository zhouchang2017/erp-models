<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/15
 * Time: 上午11:47
 */

namespace Chang\Erp\Models;


class PromotionVariant extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = [
        'variant_id',
        'product_id',
        'promotion_id',
        'promotion_type',
        'surrender_rate',
        'discount_rate',
        'quantity_limit',
        'stock',
        'sold',
        'began_at',
        'ended_at',
        'rest',
        'sign_up_id',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function signUp()
    {
        return $this->belongsTo(PromotionSignUp::class);
    }
}