<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xpubs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('chain_id', 20);
            $table->string('label');
            $table->text('xpub_key');
            $table->string('derivation_path')->default("m/84'/0'/0'/0/0");
            $table->enum('status', ['synced', 'watching', 'paused'])->default('watching');
            $table->unsignedInteger('addresses_generated')->default(0);
            $table->decimal('total_received', 20, 8)->default(0);
            $table->timestamps();

            $table->index(['user_id', 'chain_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xpubs');
    }
};
