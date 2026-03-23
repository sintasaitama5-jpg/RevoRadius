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
        Schema::create('radius_nas', function (Blueprint $table) {
            $table->id();
            $table->string('nasname', 128)->unique()->index(); // IP/Hostname
            $table->string('shortname', 32)->nullable(); // router name
            $table->string('type', 30)->default('other');
            $table->integer('ports')->nullable();
            $table->string('secret', 60); // radius secret
            $table->string('server', 64)->nullable();
            $table->string('community', 50)->nullable();
            $table->string('description', 200)->nullable();

            // Relasi ke table routers (jika NAS ini adalah router Mikrotik internal sistem)
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_nas');
    }
};