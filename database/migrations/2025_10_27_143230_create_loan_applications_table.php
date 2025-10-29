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
        Schema::create('loan_applications', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 17, 2); // Nominal pinjaman
            $table->integer('tenor_months'); // Tenor pinjaman (dalam bulan)
            $table->decimal('interest_rate', 5, 2); // Bunga per tahun
            $table->decimal('total_payment', 17, 2)->nullable(); // Total pembayaran
            $table->decimal('monthly_installment', 17, 2)->nullable(); // Cicilan per bulan
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status pengajuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
