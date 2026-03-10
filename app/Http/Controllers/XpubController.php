<?php

namespace App\Http\Controllers;

use App\Models\Xpub;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class XpubController extends Controller
{
    /**
     * Display the XPUB Vault.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $xpubs = $user->xpubs()
            ->latest()
            ->get()
            ->map(function ($xpub) {
                return [
                    'id' => $xpub->id,
                    'chain' => $xpub->chain_id, // We'll map to object on frontend
                    'key' => $xpub->xpub_key,
                    'keyTruncated' => substr($xpub->xpub_key, 0, 12) . '...' . substr($xpub->xpub_key, -8),
                    'derivationPath' => $xpub->derivation_path,
                    'addressesGenerated' => $xpub->addresses_generated,
                    'totalReceived' => (float)$xpub->total_received,
                    'status' => $xpub->status,
                    'addedAt' => $xpub->created_at->toISOString(),
                    'label' => $xpub->label,
                ];
            });

        return Inertia::render('Dashboard/XpubVault', [
            'initialXpubs' => $xpubs,
        ]);
    }

    /**
     * Store a newly registered XPUB.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'chain_id' => 'required|string|in:btc,eth,sol,matic,avax,bnb,trx,arb,op,base,near,xlm',
            'xpub_key' => 'required|string',
            'derivation_path' => "required|string|regex:/^m(\/[0-9]+'?)+$/",
        ]);

        $request->user()->xpubs()->create(array_merge($validated, [
            'status' => 'watching',
            'addresses_generated' => 0,
            'total_received' => 0,
        ]));

        return back()->with('success', 'XPUB registered successfully.');
    }

    /**
     * Update the specified XPUB status.
     */
    public function update(Request $request, Xpub $xpub)
    {
        $this->authorize('update', $xpub);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['synced', 'watching', 'paused'])],
        ]);

        $xpub->update($validated);

        return back()->with('success', 'XPUB status updated.');
    }

    /**
     * Remove the specified XPUB from storage.
     */
    public function destroy(Xpub $xpub)
    {
        $this->authorize('delete', $xpub);

        $xpub->delete();

        return back()->with('success', 'XPUB deleted successfully.');
    }
}
