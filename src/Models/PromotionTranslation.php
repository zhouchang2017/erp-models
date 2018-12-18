<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/15
 * Time: 上午11:45
 */

namespace Chang\Erp\Models;


class PromotionTranslation extends Model
{
    protected $connection = 'dealpaw';

    protected $fillable = [
        'description',
        'name',
        'rest',
        'locale_code',
        'translatable_id',
        'asset_url',
        'asset_iamge'
    ];

}