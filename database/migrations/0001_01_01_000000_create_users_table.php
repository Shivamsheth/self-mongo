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
        // USERS COLLECTION
        Schema::connection('mongodb')->create('users', function ($collection) {
            // MongoDB automatically creates "_id"
            $collection->string('name');
            $collection->string('email');
            $collection->unique('email');
            $collection->timestamp('email_verified_at')->nullable();
            $collection->string('password');
            $collection->string('remember_token')->nullable();
            $collection->timestamps(); // creates created_at & updated_at
        });

        // PASSWORD RESET TOKENS COLLECTION
        Schema::connection('mongodb')->create('password_reset_tokens', function ($collection) {
            $collection->string('email');
            $collection->string('token');
            $collection->timestamp('created_at')->nullable();
            $collection->unique('email'); // mimic primary key behavior
        });

        // SESSIONS COLLECTION
        Schema::connection('mongodb')->create('sessions', function ($collection) {
            $collection->string('id');
            $collection->index('id'); // mimic primary key behavior

            $collection->string('user_id')->nullable();
            $collection->index('user_id');

            $collection->string('ip_address')->nullable();
            $collection->text('user_agent')->nullable();
            $collection->longText('payload');
            $collection->integer('last_activity');
            $collection->index('last_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mongodb')->drop('users');
        Schema::connection('mongodb')->drop('password_reset_tokens');
        Schema::connection('mongodb')->drop('sessions');
    }
};
