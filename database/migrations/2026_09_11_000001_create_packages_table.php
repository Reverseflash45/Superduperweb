<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('project')->index();
            $table->string('tagline')->nullable();
            // Nominal disimpan sebagai integer rupiah (bukan float).
            $table->unsignedBigInteger('price')->nullable();
            $table->boolean('is_starting_price')->default(false);
            $table->string('duration_estimate')->nullable();
            $table->string('revision_count')->nullable();
            $table->json('features')->nullable();
            $table->string('demo_url')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
