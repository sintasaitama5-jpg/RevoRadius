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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password')->nullable(); // PPPoE/Hotspot Secret
            $table->string('profile_name');
            $table->decimal('monthly_price', 10, 2);
            $table->enum('status', ['ACTIVE', 'SUSPENDED', 'TERMINATED'])->default('ACTIVE');
            $table->date('billing_cycle_date'); // Tanggal jatuh tempo per bulan (misal: 05)
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
