<?php

namespace Applicazza\LaravelSynchronizer\Jobs;

use Applicazza\LaravelSynchronizer\Entities;
use DB;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class LaravelSynchronizerJob
 * @package Applicazza\LaravelSynchronizer\Jobs
 */
abstract class LaravelSynchronizerJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Applicazza\LaravelSynchronizer\Entities\Synchronization
     */
    protected $synchronization;

    /**
     * LaravelSynchronizerJob constructor.
     * @param \Applicazza\LaravelSynchronizer\Entities\Synchronization $synchronization
     */
    function __construct(Entities\Synchronization $synchronization)
    {
        $this->synchronization = $synchronization;
    }

    /**
     * @param Exception $e
     */
    public function failed(Exception $e)
    {
        $this->synchronization->stopWithError();
    }

    /**
     *
     */
    public function handle()
    {
        $this->synchronization->start();

        DB::transaction(function() {

            $this->synchronize();

            $this->synchronization->stop();

        });
    }

    /**
     * @return mixed
     */
    abstract function synchronize();
}