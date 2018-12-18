<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/6
 * Time: 10:44 AM
 */

namespace Chang\Erp\Services;

use Chang\Erp\Contracts\Trackable;
use Chang\Erp\Events\CompletedEvent;
use Chang\Erp\Events\ShippedEvent;
use Chang\Erp\Models\InventoryIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ShipmentService
{
    protected $model;

    protected $fillable = ['tracks', 'has_shipment'];

    public function __construct(Trackable $model)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function trackableShipment(Request $request)
    {
        return collect($request->all())->map(function ($item){
            $item['units'] = collect(array_get($item, 'units', []))->map(function ($unit){
                $shipment = array_except(array_get($unit, 'shipment'), 'id');

                $track = $this->model->tracks()->updateOrCreate($shipment, $shipment);

                tap($this->model->units()->getModel()::find($unit['id']), function ($itemUnit) use ($track) {
                    $itemUnit->shipment()->associate($track);
                    $itemUnit->save();
                });

                $unit['shipment'] = $track;
                $unit['shipment_track_id'] = $track['id'];
                return $unit;
            });
            return $item;
        });
    }

    protected function fillAttributeFromRequest(Request $request)
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
    }

    // 发货操作
    public function shipment(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $this->model->beforeShipped();
            $this->fillAttributeFromRequest($request);
            if ( !$this->model->isShipped() && !$this->model->has_shipment || $this->model->hasTracks()) {
                event(new ShippedEvent($this->model));
            }
            $this->model->afterShipped();
            return $this->model;
        });
    }

    // 确认收货操作
    public function completed()
    {
        return DB::transaction(function () {
            $this->model->beforeCompleted();
            event(new CompletedEvent($this->model));
            $this->model->afterCompleted();
            return $this->model;
        });
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