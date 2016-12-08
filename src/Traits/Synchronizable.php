<?php

namespace Applicazza\LaravelSynchronizer\Traits;

use Applicazza\LaravelSynchronizer\Entities\Synchronization;

/**
 * Class Synchronizable
 * @package Applicazza\LaravelSynchronizer\Traits
 */
trait Synchronizable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function synchronizations()
    {
        return $this->morphMany(Synchronization::class, 'synchronizable');
    }

    /**
     * @param string $entity
     * @return mixed
     */
    public function synchronizationForEntity(string $entity)
    {
        return $this->synchronizations()->where('entity', '=', $entity)->first();
    }
}