<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('key_hash');
            $table->string('key_prefix', 20);
            $table->boolean('is_test')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->json('ip_whitelist')->nullable();
            $table->unsignedInteger('requests_today')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'is_test']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
