<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/24
 * Time: 9:45 AM
 */

namespace Chang\Erp\Media\Filesystem;

use Spatie\MediaLibrary\Filesystem\Filesystem as BaseFilesystem;
use Spatie\MediaLibrary\Models\Media;

class Filesystem extends BaseFilesystem
{
    public function removeAllFiles(Media $media)
    {
        if ($media->getDiskDriverName() === 'qiniu') {
            $this->removeFile($media, $media->id . '/' . $media->file_name);
            return;
        }
        parent::removeAllFiles($media);
    }


}