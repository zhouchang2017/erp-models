<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/25
 * Time: 11:49 AM
 */

namespace Chang\Erp\Media;


use App\Modules\DealpawProduct\Models\ProductImage;

trait ProductImageTrait
{

    public function media()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }


    public function addMedia($file)
    {
        return app(MediaAdderFactory::class)->create($this, $file);
    }

    public function getFullUrl($field = 'image')
    {
        return $this->getFirstMedia() ? $this->getFirstMedia()->{$field} : null;
    }

    public function getThumbUrl(string $url = null)
    {
        return $url ? $url . '?imageView2/1/w/80/h/80/interlace/1/q/100' : null;
    }

    public function getFirstMediaUrl($field = 'image')
    {
        return $this->getFirstMedia() ? $this->getFirstMedia()->{$field} : null;
    }

    public function getFirstMedia()
    {
        return $this->media()->first();
    }

    /**
     * Copy a file to the medialibrary.
     *
     * @param string|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     */
    public function copyMedia($file)
    {
        // TODO: Implement copyMedia() method.
    }

    /**
     * Determine if there is media in the given collection.
     *
     *
     * @return bool
     */
    public function hasMedia(): bool
    {
        return $this->media()->count() > 0;
    }

    /**
     * Get media collection by its collectionName.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMedia()
    {
        return $this->media()->get()->sortBy('position');
    }

    /**
     * Remove all media in the given collection.
     *
     */
    public function clearMediaCollection()
    {
        $this->getMedia()
            ->each->delete();


        if ($this->mediaIsPreloaded()) {
            unset($this->media);
        }

        return $this;
    }

}