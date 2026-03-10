<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of API keys.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $keys = $user->apiKeys()
            ->orderBy('is_test')
            ->latest()
            ->get()
            ->map(function ($key) {
                return [
                    'id' => $key->id,
                    'name' => $key->name,
                    'keyMasked' => $key->key_prefix . str_repeat('•', 20),
                    'isTest' => $key->is_test,
                    'createdAt' => $key->created_at->toISOString(),
                    'lastUsed' => $key->last_used_at ? $key->last_used_at->diffForHumans() : null,
                    'requestsToday' => $key->requests_today,
                    'ipWhitelist' => $key->ip_whitelist ?? [],
                ];
            });

        return Inertia::render('Developer/ApiKeys', [
            'initialApiKeys' => $keys,
        ]);
    }

    /**
     * Store a newly created API key.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_test' => 'boolean',
        ]);

        $isTest = $validated['is_test'] ?? false;
        $plainKey = ApiKey::generateKey($isTest);

        $apiKey = $request->user()->apiKeys()->create([
            'name' => $validated['name'],
            'key_hash' => Hash::make($plainKey),
            'key_prefix' => substr($plainKey, 0, 12),
            'is_test' => $isTest,
            'ip_whitelist' => [],
            'requests_today' => 0,
        ]);

        // In a real app we'd flash the plain key just once to a session variable.
        // For the demo frontend, we return it so the modal can show it.
        return back()->with('flash_new_api_key', $plainKey);
    }

    /**
     * Revoke the specified API key.
     */
    public function destroy(ApiKey $apiKey)
    {
        $this->authorize('delete', $apiKey);

        $apiKey->delete();

        return back()->with('success', 'API key revoked successfully.');
    }
}
