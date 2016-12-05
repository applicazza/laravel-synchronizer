<?php

namespace Applicazza\LaravelSynchronizer\Console\Commands;

use Applicazza\LaravelSynchronizer\Entities;
use Illuminate\Console\Command;

class ScheduleSynchronization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronization:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule synchronizations';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $synchronizations = Entities\Synchronization::query()
            ->notQueued()
            ->notProcessing()
            ->processable()
            ->get();

        foreach ($synchronizations as $synchronization) {

            $class = config("synchronizer.synchronizations.{$synchronization->entity}.job");

            if (class_exists($class)) {

                $synchronization->queue();

                dispatch(new $class($synchronization));

            }

        }
    }
}
