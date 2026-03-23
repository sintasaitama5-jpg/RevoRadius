<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('radius_sync_logs', function (Blueprint $table) {
            $table->id();

            $table->string('model_type'); // misal: 'RadiusUsersMap', 'RadiusGroupsMap'
            $table->unsignedBigInteger('model_id');

            $table->string('action', 50); // create, update, delete
            $table->json('payload')->nullable();
            $table->string('status', 20)->default('pending'); // pending, success, failed
            $table->text('message')->nullable(); // detail error / success

            // Waktu dieksekusi di background job
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_sync_logs');
    }
};