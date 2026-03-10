<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeveloperToolsController extends Controller
{
    /**
     * Display the testnet sandbox page.
     */
    public function sandbox(Request $request): Response
    {
        $user = $request->user();
        
        // We'll pass minimal config since the Sandbox components primarily
        // rely on client-side state for the "simulator" logic.
        return Inertia::render('Developer/Sandbox', [
            'hasTestApiKey' => $user->apiKeys()->where('is_test', true)->exists(),
        ]);
    }

    /**
     * Display the API playground.
     */
    public function playground(Request $request): Response
    {
        $user = $request->user();
        $liveKeys = $user->apiKeys()->where('is_test', false)->get(['id', 'name']);
        $testKeys = $user->apiKeys()->where('is_test', true)->get(['id', 'name']);

        return Inertia::render('Developer/Playground', [
            'availableKeys' => [
                'live' => $liveKeys,
                'test' => $testKeys,
            ]
        ]);
    }
}
