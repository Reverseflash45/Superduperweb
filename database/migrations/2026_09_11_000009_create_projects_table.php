<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('inquiry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            // Nominal disimpan sebagai integer rupiah (bukan float).
            $table->unsignedBigInteger('deal_price')->default(0);
            $table->unsignedTinyInteger('dp_percentage')->default(50);
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('antrian')->index();
            $table->string('invoice_code', 64)->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
