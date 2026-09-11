<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->index();
            $table->string('project_type')->default('klien')->index();
            $table->string('client_name')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('summary')->nullable();
            $table->text('problem')->nullable();
            $table->text('solution')->nullable();
            $table->json('features')->nullable();
            $table->json('tech_stack')->nullable();
            $table->string('demo_url')->nullable();
            $table->date('completed_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
