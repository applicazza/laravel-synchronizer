<?php

namespace Applicazza\LaravelSynchronizer;

use Applicazza\LaravelSynchronizer\Entities;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Synchronizer
 * @package Applicazza\LaravelSynchronizer
 */
class Synchronizer
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $synchronizable
     * @param string $entity
     * @param int $interval
     * @return \Applicazza\LaravelSynchronizer\Entities\Synchronization
     */
    public function add(Model $synchronizable, string $entity, $interval = 60) : Entities\Synchronization
    {
        $synchronization = new Entities\Synchronization;

        $synchronization->fill([
            'entity' => $entity,
            'interval' => $interval,
        ]);

        $synchronization->synchronizable()->associate($synchronizable);

        $synchronization->save();

        return $synchronization;
    }

    /**
     * @param Model $synchronizable
     * @param string $entity
     * @return bool
     */
    public function remove(Model $synchronizable, string $entity) : boolean
    {
        $synchronization = $synchronizable->synchronizationForEntity($entity);

        if ($synchronization)
            return $synchronization->delete();

        return false;
    }
}