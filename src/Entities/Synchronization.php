<?php

namespace Applicazza\LaravelSynchronizer\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Synchronization
 * @package Applicazza\LaravelSynchronizer\Entities
 */
class Synchronization extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = [

        'deleted_at',
        'process_after',
        'processed_at',

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function synchronizable()
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @param $type
     * @return Builder
     */
    public function scopeOfType(Builder $query, $type)
    {
        return $query->where('synchronizable_type', '=', $type);
    }

    /**
     * @param Builder $query
     * @param $entity
     * @return Builder
     */
    public function scopeOfEntity(Builder $query, $entity)
    {
        return $query->where('entity', '=', $entity);
    }

    /**
     * @param Builder $query
     * @param $entity
     * @return Builder
     */
    public function scopeOf(Builder $query, $type, $entity)
    {
        return $query->where('synchronizable_type', '=', $type)->where('entity', '=', $entity);
    }

    /**
     * @param Builder $query
     * @param Carbon $date
     * @return Builder
     */
    public function scopeProcessableAfter(Builder $query, Carbon $date)
    {
        return $query->where('processable_after', '<=', $date);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeProcessable(Builder $query)
    {
        return $query->where('processable_after', '<=', Carbon::now('UTC'));
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeQueued(Builder $query)
    {
        return $query->where('is_queued', '=', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotQueued(Builder $query)
    {
        return $query->where('is_queued', '=', false);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeProcessing(Builder $query)
    {
        return $query->where('is_processing', '=', true);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotProcessing(Builder $query)
    {
        return $query->where('is_processing', '=', false);
    }

    /**
     * @param bool $save
     */
    public function start($save = true)
    {
        $this->is_processing = true;

        if ($save)
            $this->save();
    }

    /**
     * @param bool $save
     */
    public function queue($save = true)
    {
        $this->is_queued = true;

        if ($save)
            $this->save();
    }

    /**
     * @param Carbon|null $date
     * @param bool $save
     */
    public function stop(Carbon $date = null, $save = true)
    {
        $this->is_processing = false;
        $this->is_queued = false;

        if (is_null($date))
            $date = Carbon::now('UTC');

        $this->processed_at = $date;

        if ($save)
            $this->save();
    }

    /**
     * @param bool $save
     */
    public function stopWithError($save = true)
    {
        $this->is_processing = false;
        $this->is_queued = false;

        if ($save)
            $this->save();
    }
}
