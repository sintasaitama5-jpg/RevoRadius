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
        Schema::create('radius_users_maps', function (Blueprint $table) {
            $table->id();
            // Identitas utama user di radius (radcheck username)
            $table->string('username', 64)->unique();
            $table->string('attribute', 64)->default('Cleartext-Password');
            $table->string('op', 2)->default(':=');
            $table->string('value', 253);

            // Relasi ke table vouchers / customers (polymorphic atau id langsung)
            // Sebagai MVP, kita gunakan type dan id untuk membedakan PPPoE / Hotspot voucher
            $table->string('member_type')->nullable(); // misal: App\Models\Voucher, App\Models\CustomerService
            $table->unsignedBigInteger('member_id')->nullable();

            // Nama group profile yang digunakan (menyambung ke radusergroup)
            $table->string('groupname', 64)->nullable()->index();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['member_type', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radius_users_maps');
    }
};