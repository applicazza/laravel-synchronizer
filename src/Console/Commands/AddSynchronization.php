<?php

namespace Applicazza\LaravelSynchronizer\Console\Commands;

use Applicazza\LaravelSynchronizer\Entities;
use Illuminate\Console\Command;

class AddSynchronization extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronization:add {entity} {synchronizable_id} {--interval=60}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add synchronization';

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
        $class = config("synchronizer.synchronizations.{$this->argument('entity')}.model");

        if (!class_exists($class))
            return $this->error('Entity is not mapped in synchronizer configuration file');

        $object = $class::find($this->argument('synchronizable_id'));

        if (!$object)
            return $this->error('Entity was not found in database');

        $synchronization = new Entities\Synchronization;

        $synchronization->synchronizable()->associate($object);
        $synchronization->entity = $this->argument('entity');
        $synchronization->interval = $this->option('interval');
        $synchronization->save();
    }
}
