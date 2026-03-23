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
        Schema::create('radius_groups_maps', function (Blueprint $table) {
            $table->id();
            // Nama profile/group (radgroupreply / radgroupcheck)
            $table->string('groupname', 64)->index();
            $table->string('attribute', 64);
            $table->string('op', 2)->default('=');
            $table->string('value', 253);
            $table->enum('type', ['check', 'reply'])->default('reply'); // radgroupcheck atau radgroupreply

            // Relasi ke HotspotProfile atau PppoeProfile
            $table->string('profile_type')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();

            $table->timestamps();

            $table->index(['profile_type', 'profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_groups_maps');
    }
};