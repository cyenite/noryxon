<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing xpubs data into wallets table
        $xpubs = DB::table('xpubs')->get();

        foreach ($xpubs as $xpub) {
            DB::table('wallets')->insert([
                'user_id' => $xpub->user_id,
                'type' => 'xpub',
                'provider' => 'custom',
                'chain_id' => $xpub->chain_id,
                'label' => $xpub->label,
                'status' => $xpub->status === 'synced' ? 'connected' : ($xpub->status === 'watching' ? 'watching' : 'paused'),
                'xpub_key' => $xpub->xpub_key,
                'derivation_path' => $xpub->derivation_path,
                'addresses_generated' => $xpub->addresses_generated,
                'total_received' => $xpub->total_received,
                'created_at' => $xpub->created_at,
                'updated_at' => $xpub->updated_at,
            ]);
        }

        // Add wallet_id to payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('wallet_id')->nullable()->after('xpub_id')->constrained('wallets')->nullOnDelete();
        });

        // Map existing xpub_id references to new wallet_id
        $walletMap = DB::table('wallets')
            ->where('type', 'xpub')
            ->pluck('id', 'label')
            ->toArray();

        // Map by matching xpub records to wallet records via user_id + chain_id + label
        $xpubToWallet = [];
        foreach ($xpubs as $xpub) {
            $wallet = DB::table('wallets')
                ->where('type', 'xpub')
                ->where('user_id', $xpub->user_id)
                ->where('chain_id', $xpub->chain_id)
                ->where('xpub_key', $xpub->xpub_key)
                ->first();

            if ($wallet) {
                $xpubToWallet[$xpub->id] = $wallet->id;
            }
        }

        foreach ($xpubToWallet as $xpubId => $walletId) {
            DB::table('payments')
                ->where('xpub_id', $xpubId)
                ->update(['wallet_id' => $walletId]);
        }
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
        });
    }
};
