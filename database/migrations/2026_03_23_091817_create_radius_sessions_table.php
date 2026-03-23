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
        // Table ini adalah snapshot radacct yang masih "terhubung" (active sessions)
        // Di FreeRADIUS biasa pakai `radacct` tapi start time != null & stop time == null
        // Atau via mikrotik API, tapi ini buat referensi cepat.
        Schema::create('radius_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('username', 64)->index();
            $table->string('acctsessionid', 64)->index();
            $table->string('nasipaddress', 15)->index();
            $table->string('framedipaddress', 15)->nullable();
            $table->string('callingstationid', 50)->nullable(); // mac address

            $table->dateTime('acctstarttime')->index();
            $table->dateTime('last_update_time')->nullable();

            // Stats real-time (last interim-update)
            $table->bigInteger('acctinputoctets')->default(0); // DL
            $table->bigInteger('acctoutputoctets')->default(0); // UL

            // Relasi ke tabel internal
            $table->foreignId('radius_users_map_id')->nullable()->constrained('radius_users_maps')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_sessions');
    }
};