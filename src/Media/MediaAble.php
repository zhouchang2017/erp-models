<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/25
 * Time: 2:10 PM
 */

namespace Chang\Erp\Media;


interface MediaAble
{
    /**
     * Set the polymorphic relation.
     *
     * @return mixed
     */
    public function media();

    /**
     * Move a file to the medialibrary.
     *
     */
    public function addMedia($file);

    /**
     * Copy a file to the medialibrary.
     *
     */
    public function copyMedia($file);

    /**
     * Determine if there is media in the given collection.
     *
     * @return bool
     */
    public function hasMedia() : bool;

    /**
     * Get media collection by its collectionName.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMedia();

    /**
     * Remove all media in the given collection.
     *
     */
    public function clearMediaCollection();

}