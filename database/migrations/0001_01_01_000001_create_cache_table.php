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
        // CACHE COLLECTION
        Schema::connection('mongodb')->create('cache', function ($collection) {
            $collection->string('key');
            $collection->mediumText('value');
            $collection->integer('expiration');
            $collection->unique('key'); // mimic primary key
        });

        // CACHE LOCKS COLLECTION
        Schema::connection('mongodb')->create('cache_locks', function ($collection) {
            $collection->string('key');
            $collection->string('owner');
            $collection->integer('expiration');
            $collection->unique('key'); // mimic primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('cache');
        Schema::connection('mongodb')->drop('cache_locks');
    }
};
