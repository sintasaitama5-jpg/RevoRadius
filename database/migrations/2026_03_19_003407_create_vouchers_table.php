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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_batch_id')->nullable()->constrained('voucher_batches')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('password')->nullable();
            $table->string('profile_name');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['UNPAID', 'PAID', 'ACTIVE', 'EXPIRED'])->default('UNPAID');
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
        Schema::dropIfExists('vouchers');
    }
};
