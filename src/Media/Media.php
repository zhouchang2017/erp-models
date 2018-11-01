<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/1
 * Time: 3:02 PM
 */

namespace Chang\Erp\Media;

use Chang\Erp\Observers\MediaObserver;
use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Illuminate\Support\Facades\Storage;

class Media extends BaseMedia
{
    protected $connection = 'mysql';

    protected static function boot()
    {
        parent::boot();
        self::observe(MediaObserver::class);
    }


    public static function getDisk($name)
    {
        return Storage::disk($name);
    }

    public function getQiniuUrl()
    {
        $disk = self::getDisk('qiniu');
        return $disk->url($this->id . '/' . $this->file_name);
    }
}