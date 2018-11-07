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
    protected $statusField = 'status';

    protected $shippedValue = 3;

    protected $shippedAtField = 'shipped_at';

    protected $completedValue = 4;

    protected $completedAtField = 'completed_at';

    public function tracks()
    {
        return $this->morphMany(ShipmentTrack::class, 'trackable');
    }

    public function track()
    {
        return $this->morphOne(ShipmentTrack::class, 'trackable');
    }

    public function hasTracks()
    {
        return $this->tracks()->count() > 0;
    }

    public function statusWhenShippedValue(): int
    {
        return $this->shippedValue;
    }

    public function getStatusField(): string
    {
        return $this->statusField;
    }

    public function getShippedAtField(): string
    {
        return $this->shippedAtField;
    }

    public function statusWhenCompletedValue(): int
    {
        return $this->completedValue;
    }

    public function getCompletedAtField(): string
    {
        return $this->completedAtField;
    }

    /**
     * 改变发货状态，标记为已发货，写入发货时间
     */
    public function statusToShipped()
    {
        if ($this->{$this->getStatusField()} < $this->statusWhenShippedValue()) {
            $this->{$this->getStatusField()} = $this->statusWhenShippedValue();
            $this->{$this->getShippedAtField()} = now();
            $this->save();
        }
    }

    /**
     * 改变完成状态，标记为已完成，写入完成时间
     */
    public function statusToCompleted()
    {
        if ((int)$this->{$this->getStatusField()} === $this->statusWhenShippedValue()) {
            $this->{$this->getStatusField()} = $this->statusWhenCompletedValue();
            $this->{$this->getCompletedAtField()} = now();
            $this->save();
        }
    }

}