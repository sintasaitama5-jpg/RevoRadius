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
        Schema::create('radius_accounting', function (Blueprint $table) {
            $table->id('radacctid'); // FreeRADIUS native primary key id

            $table->string('acctsessionid', 64)->index();
            $table->string('acctuniqueid', 32)->unique();
            $table->string('username', 64)->index();
            $table->string('groupname', 64)->nullable();

            $table->string('nasipaddress', 15)->index();
            $table->string('nasportid', 32)->nullable();
            $table->string('nasporttype', 32)->nullable();

            $table->dateTime('acctstarttime')->nullable()->index();
            $table->dateTime('acctupdatetime')->nullable();
            $table->dateTime('acctstoptime')->nullable()->index();

            $table->integer('acctinterval')->nullable();
            $table->unsignedInteger('acctsessiontime')->nullable(); // detik durasi session

            $table->string('acctauthentic', 32)->nullable();
            $table->string('connectinfo_start', 128)->nullable();
            $table->string('connectinfo_stop', 128)->nullable();

            $table->bigInteger('acctinputoctets')->nullable(); // Download bytes
            $table->bigInteger('acctoutputoctets')->nullable(); // Upload bytes

            $table->string('calledstationid', 50)->nullable();
            $table->string('callingstationid', 50)->nullable(); // MAC Address user biasa
            $table->string('acctterminatecause', 32)->nullable();
            $table->string('servicetype', 32)->nullable();
            $table->string('framedprotocol', 32)->nullable();
            $table->string('framedipaddress', 15)->nullable();

            // Relasi tambahan ke data app
            $table->foreignId('user_map_id')->nullable()->constrained('radius_users_maps')->nullOnDelete();

            $table->timestamps();

            // Indeks untuk report cepat berdasarkan waktu dan user
            $table->index(['username', 'acctstarttime', 'acctstoptime'], 'idx_user_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_accounting');
    }
};