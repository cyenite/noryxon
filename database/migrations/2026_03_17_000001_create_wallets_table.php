<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['exchange', 'software', 'xpub', 'manual']);
            $table->string('provider', 50); // binance, metamask, ledger, custom, etc.
            $table->string('chain_id', 20)->nullable(); // null for multi-chain exchanges
            $table->string('label');
            $table->enum('status', ['connected', 'syncing', 'watching', 'paused', 'error'])->default('watching');

            // Address-based fields (software wallet, manual)
            $table->string('address', 255)->nullable();

            // XPub fields (carried over from existing xpubs table)
            $table->text('xpub_key')->nullable();
            $table->string('derivation_path')->nullable();

            // Exchange API fields (encrypted at application level)
            $table->text('api_key_encrypted')->nullable();
            $table->text('api_secret_encrypted')->nullable();
            $table->text('api_passphrase_encrypted')->nullable();

            // Aggregated stats
            $table->unsignedInteger('addresses_generated')->default(0);
            $table->decimal('total_received', 20, 8)->default(0);
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'chain_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
