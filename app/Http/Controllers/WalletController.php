<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    /**
     * Display the Wallets page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $wallets = $user->wallets()
            ->latest()
            ->get()
            ->map(function ($wallet) {
                return [
                    'id' => $wallet->id,
                    'type' => $wallet->type,
                    'provider' => $wallet->provider,
                    'chain' => $wallet->chain_id,
                    'label' => $wallet->label,
                    'status' => $wallet->status,
                    'displayIdentifier' => $wallet->display_identifier,
                    'address' => $wallet->address ? substr($wallet->address, 0, 10) . '...' . substr($wallet->address, -6) : null,
                    'xpubKey' => $wallet->xpub_key ? substr($wallet->xpub_key, 0, 12) . '...' . substr($wallet->xpub_key, -8) : null,
                    'derivationPath' => $wallet->derivation_path,
                    'hasApiKey' => !empty($wallet->api_key_encrypted),
                    'addressesGenerated' => $wallet->addresses_generated,
                    'totalReceived' => (float)$wallet->total_received,
                    'lastSyncedAt' => $wallet->last_synced_at?->toISOString(),
                    'addedAt' => $wallet->created_at->toISOString(),
                ];
            });

        return Inertia::render('Dashboard/Wallets', [
            'initialWallets' => $wallets,
        ]);
    }

    /**
     * Store a newly connected wallet.
     */
    public function store(Request $request)
    {
        $type = $request->input('type');

        // Common validation rules
        $rules = [
            'type' => 'required|string|in:exchange,software,xpub,manual',
            'label' => 'required|string|max:255',
        ];

        // Type-specific validation
        $rules = match ($type) {
            'exchange' => array_merge($rules, [
                'provider' => 'required|string|in:binance,coinbase,kraken,kucoin,bybit,okx',
                'api_key' => 'required|string',
                'api_secret' => 'required|string',
                'api_passphrase' => 'nullable|string',
            ]),
            'software' => array_merge($rules, [
                'provider' => 'required|string|in:metamask,trustwallet,phantom,rabby,rainbow,generic',
                'chain_id' => 'required|string|in:btc,eth,sol,matic,avax,bnb,trx,arb,op,base,near,xlm',
                'address' => 'required|string|max:255',
            ]),
            'xpub' => array_merge($rules, [
                'chain_id' => 'required|string|in:btc,eth,sol,matic,avax,bnb,trx,arb,op,base,near,xlm',
                'xpub_key' => 'required|string',
                'derivation_path' => "required|string|regex:/^m(\/[0-9]+'?)+$/",
            ]),
            'manual' => array_merge($rules, [
                'chain_id' => 'required|string|in:btc,eth,sol,matic,avax,bnb,trx,arb,op,base,near,xlm',
                'address' => 'required|string|max:255',
            ]),
            default => $rules,
        };

        $validated = $request->validate($rules);

        // Build the wallet data
        $walletData = [
            'type' => $validated['type'],
            'label' => $validated['label'],
            'provider' => $validated['provider'] ?? 'custom',
            'chain_id' => $validated['chain_id'] ?? null,
            'status' => 'watching',
            'addresses_generated' => 0,
            'total_received' => 0,
        ];

        // Type-specific fields
        match ($type) {
            'exchange' => $walletData = array_merge($walletData, [
                'api_key_encrypted' => $validated['api_key'],
                'api_secret_encrypted' => $validated['api_secret'],
                'api_passphrase_encrypted' => $validated['api_passphrase'] ?? null,
                'status' => 'connected',
            ]),
            'software' => $walletData = array_merge($walletData, [
                'address' => $validated['address'],
            ]),
            'xpub' => $walletData = array_merge($walletData, [
                'xpub_key' => $validated['xpub_key'],
                'derivation_path' => $validated['derivation_path'],
            ]),
            'manual' => $walletData = array_merge($walletData, [
                'address' => $validated['address'],
            ]),
            default => null,
        };

        $request->user()->wallets()->create($walletData);

        return back()->with('success', 'Wallet connected successfully.');
    }

    /**
     * Update the specified wallet status.
     */
    public function update(Request $request, Wallet $wallet)
    {
        // Authorization: ensure wallet belongs to user
        if ($wallet->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['connected', 'syncing', 'watching', 'paused'])],
        ]);

        $wallet->update($validated);

        return back()->with('success', 'Wallet status updated.');
    }

    /**
     * Remove the specified wallet.
     */
    public function destroy(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== $request->user()->id) {
            abort(403);
        }

        $wallet->delete();

        return back()->with('success', 'Wallet disconnected successfully.');
    }
}
