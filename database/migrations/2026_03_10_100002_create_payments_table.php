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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('xpub_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tx_hash', 130)->unique();
            $table->decimal('amount', 20, 8);
            $table->string('token', 20);
            $table->string('chain_id', 20);
            $table->enum('status', ['confirmed', 'pending', 'processing', 'expired'])->default('pending');
            $table->unsignedInteger('confirmations')->default(0);
            $table->unsignedInteger('required_confirmations')->default(3);
            $table->string('customer_wallet')->nullable();
            $table->string('merchant_wallet')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'chain_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
