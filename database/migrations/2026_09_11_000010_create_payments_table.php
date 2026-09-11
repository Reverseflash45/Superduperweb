<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type')->default('dp');
            // Nominal disimpan sebagai integer rupiah (bukan float).
            $table->unsignedBigInteger('amount')->default(0);
            $table->string('method')->default('transfer');
            $table->string('proof_path')->nullable();
            $table->string('status')->default('menunggu-verifikasi')->index();
            $table->date('paid_at')->nullable();
            $table->text('note')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
