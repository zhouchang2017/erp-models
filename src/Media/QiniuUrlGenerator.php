<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:27 PM
 */

namespace Chang\Erp\Media;


use DateTimeInterface;
use Spatie\MediaLibrary\UrlGenerator\BaseUrlGenerator;

class QiniuUrlGenerator extends BaseUrlGenerator
{

    /**
     * Get the url for a media item.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->media->getQiniuUrl();
    }

    /**
     * Get the temporary url for a media item.
     *
     * @param DateTimeInterface $expiration
     * @param array $options
     *
     * @return string
     */
    public function getTemporaryUrl(DateTimeInterface $expiration, array $options = []): string
    {
        return $this->media->getQiniuUrl();
    }

    /**
     * Get the url to the directory containing responsive images.
     *
     * @return string
     */
    public function getResponsiveImagesDirectoryUrl(): string
    {
        return $this->media->getQiniuUrl();
    }
}