<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/6
 * Time: 10:44 AM
 */

namespace Chang\Erp\Services;

use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Events\Shipped;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ShipmentService
{
    protected $model;

    protected $fillable = ['tracks', 'has_shipment'];

    public function __construct(Trackable $model)
    {
        $this->model = $model;
    }

    public function setRequestKey(array $key)
    {
        $this->requestKey = $key;
        return $this;
    }

    public function fillAttributeFromRequest(Request $request)
    {
        if ($request->has('tracks')) {
            $data = collect($request->input('tracks', []));
            // update updated tracks
            $originTracks = $this->getOriginTracks($data)->map(function ($track) {
                return tap($this->model->tracks()->where('id', $track['id'])->first(),
                    function ($shipment) use ($track) {
                        if ($shipment) {
                            $class = get_class($shipment);
                            $shipment->update(array_only($track, app($class)->getFillable()));
                        }
                    });
            });
            // remove deleted tracks
            $this->removeTracks($originTracks->pluck('id'), $this->model);
            // add new tracks
            $this->getAddTracks($data)->map(function ($track) {
                return $this->model->tracks()->create($track);
            });
        }

        if ($request->has('has_shipment')) {
            $this->model->update(['has_shipment' => $request->get('has_shipment')]);
        }

        if ( !$this->model->isShipped() && !$this->model->has_shipment || $this->model->hasTracks()) {
            event(new Shipped($this->model));
        }
    }


    protected function getOriginTracks(Collection $collection)
    {
        return $collection->filter(function ($item) {
            return !is_null($item['id']);
        });
    }

    protected function getAddTracks(Collection $collection)
    {
        return $collection->filter(function ($item) {
            return is_null($item['id']);
        });
    }

    protected function removeTracks($remainingIds, Trackable $model)
    {
        $tracks = $model->tracks;
        $tracks->pluck('id')->diff($remainingIds)->each(function ($id) use ($tracks) {
            if ($shipment = $tracks->where('id', $id)->first()) {
                $shipment->delete();
            }
        });

        return $remainingIds;
    }
}