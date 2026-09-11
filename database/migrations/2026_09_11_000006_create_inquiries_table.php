<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_name')->nullable();
            $table->string('whatsapp');
            $table->string('email')->nullable();
            $table->string('service_type');
            $table->string('budget_range')->nullable();
            $table->string('deadline')->nullable();
            $table->text('description');
            $table->string('status')->default('baru')->index();
            $table->text('admin_notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
