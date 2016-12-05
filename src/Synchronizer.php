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
}