<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 3:54 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\ShipmentTrack;

trait TrackableTrait
{
    public function tracks()
    {
        return $this->morphMany(ShipmentTrack::class, 'trackable');
    }
}