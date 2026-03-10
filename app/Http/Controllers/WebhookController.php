<?php

namespace App\Http\Controllers;

use App\Models\WebhookEndpoint;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WebhookController extends Controller
{
    /**
     * Display the webhook configuration and deliveries.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get the active endpoint (or just the latest for now)
        $endpoint = $user->webhookEndpoints()->latest()->first();

        // Get deliveries if we have an endpoint
        $deliveries = [];
        if ($endpoint) {
            $deliveries = $endpoint->deliveries()
                ->latest()
                ->limit(50)
                ->get()
                ->map(function ($del) {
                    return [
                        'id' => (string)$del->id,
                        'event' => $del->event,
                        'url' => $del->endpoint->url,
                        'statusCode' => $del->status_code,
                        'success' => $del->success,
                        'responseTime' => $del->response_time_ms,
                        'timestamp' => $del->created_at->toISOString(),
                        'payloadSize' => strlen(json_encode($del->payload)),
                        'attempt' => $del->attempt,
                        'signature' => substr($del->signature, 0, 16) . '...',
                        'payload' => json_encode($del->payload, JSON_PRETTY_PRINT),
                    ];
                });
        }

        return Inertia::render('Developer/Webhooks', [
            'initialConfig' => $endpoint ? [
                'url' => $endpoint->url,
                'events' => $endpoint->subscribed_events,
                'signingSecret' => $endpoint->signing_secret, // Let's decrypt or just provide it for demo
            ] : null,
            'initialDeliveries' => $deliveries,
        ]);
    }

    /**
     * Store or update webhook configuration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:255',
            'events' => 'required|array|min:1',
            'events.*' => 'string',
        ]);

        $user = $request->user();
        
        // Find existing or create new
        $endpoint = $user->webhookEndpoints()->first() ?? new WebhookEndpoint(['user_id' => $user->id]);
        
        $endpoint->fill([
            'url' => $validated['url'],
            'subscribed_events' => $validated['events'],
        ]);
        
        if (!$endpoint->exists) {
            $endpoint->signing_secret = WebhookEndpoint::generateSigningSecret();
            $endpoint->is_active = true;
        }
        
        $endpoint->save();

        return back()->with('success', 'Webhook configuration saved.');
    }
}
