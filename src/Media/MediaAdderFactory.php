<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/10/25
 * Time: 11:23 AM
 */

namespace Chang\Erp\Media;


use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\FileAdder\FileAdderFactory;

class MediaAdderFactory extends FileAdderFactory
{
    public static function create(Model $subject, $file)
    {
        return app(MediaAdder::class)
            ->setSubject($subject)
            ->setFile($file);
    }
}