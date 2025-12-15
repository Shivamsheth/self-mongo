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
        Schema::connection('mongodb')->create('personal_access_tokens', function ($collection) {
            // MongoDB automatically generates "_id" as primary key (no need for $table->id()).

            // Equivalent of $table->morphs('tokenable')
            $collection->string('tokenable_type');
            $collection->string('tokenable_id');
            $collection->index(['tokenable_type', 'tokenable_id']);

            // Regular columns (kept exactly as in your SQL migration)
            $collection->string('name');
            $collection->string('token', 64);
            $collection->text('abilities')->nullable();
            $collection->timestamp('last_used_at')->nullable();
            $collection->timestamp('expires_at')->nullable();
            $collection->timestamps(); // creates created_at and updated_at fields

            // Indexes and unique constraints
            $collection->unique('token');
            $collection->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('personal_access_tokens');
    }
};
