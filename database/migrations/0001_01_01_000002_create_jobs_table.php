<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // JOBS COLLECTION
        Schema::connection('mongodb')->create('jobs', function ($collection) {
            // MongoDB auto-creates "_id" (no need for $table->id())
            $collection->string('queue');
            $collection->index('queue'); // keep index on queue

            $collection->longText('payload');
            $collection->integer('attempts');
            $collection->integer('reserved_at')->nullable();
            $collection->integer('available_at');
            $collection->integer('created_at');
        });

        // JOB BATCHES COLLECTION
        Schema::connection('mongodb')->create('job_batches', function ($collection) {
            $collection->string('id'); // mimic primary key
            $collection->unique('id');

            $collection->string('name');
            $collection->integer('total_jobs');
            $collection->integer('pending_jobs');
            $collection->integer('failed_jobs');
            $collection->longText('failed_job_ids');
            $collection->mediumText('options')->nullable();
            $collection->integer('cancelled_at')->nullable();
            $collection->integer('created_at');
            $collection->integer('finished_at')->nullable();
        });

        // FAILED JOBS COLLECTION
        Schema::connection('mongodb')->create('failed_jobs', function ($collection) {
            // MongoDB adds "_id" automatically
            $collection->string('uuid');
            $collection->unique('uuid');

            $collection->text('connection');
            $collection->text('queue');
            $collection->longText('payload');
            $collection->longText('exception');
            $collection->timestamp('failed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('jobs');
        Schema::connection('mongodb')->drop('job_batches');
        Schema::connection('mongodb')->drop('failed_jobs');
    }
};
