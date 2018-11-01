<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:53 PM
 */

namespace Chang\Erp\Observers;

use Chang\Erp\Media\Media;

class MediaObserver
{
    public function created(Media $media)
    {
        if ($media->collection_name === 'product_image') {
            $media->model->images()->create(['image' => $media->getQiniuUrl()]);
        }
    }

    public function saved(Media $media)
    {

    }

    protected function getModel()
    {

    }

    public function deleted(Media $media)
    {
        if ($media->collection_name === 'product_image') {
            $url = $media->getQiniuUrl();
            tap($media->model->images->filter(function ($image) use ($url) {
                return $image->image == $url;
            })->first(), function ($productImage) {
                if ( !is_null($productImage)) {
                    $productImage->delete();
                }
            });
        }
    }
}