<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('language_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('from_locale', 2)->nullable();
            $table->string('to_locale', 2);
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index('to_locale');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('language_activities');
    }
};