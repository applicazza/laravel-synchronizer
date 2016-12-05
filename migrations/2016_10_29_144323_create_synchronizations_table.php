<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSynchronizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('synchronizations', function (Blueprint $table) {

            $table->increments('id');
            $table->string('synchronizable_id');
            $table->string('synchronizable_type');
            $table->string('entity');
            $table->integer('interval')->default(60);
            $table->boolean('is_queued')->default(false);
            $table->boolean('is_processing')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('processable_after')->generated('(coalesce(`processed_at`,"1970-01-01 00:00:01") + interval `interval` second)')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['synchronizable_id', 'synchronizable_type', 'entity'], 'synchronizations_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('synchronizations');
    }
}
