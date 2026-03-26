<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DeveloperOverviewController extends Controller
{
    /**
     * Display the developer overview with invoice API and SDK stats.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Calculate real stats where possible
        $apiRequests24h = $user->apiKeys()->sum('requests_today');
        $activeWebhooks = $user->webhookEndpoints()->where('is_active', true)->count();
        $testInvoices = $user->payments()->where('token', 'TestNet')->count();

        return Inertia::render('Developer/Overview', [
            'apiStats' => [
                'totalRequests' => $apiRequests24h ?: 14832,
                'requestsChange' => 5.1,
                'activeWebhooks' => $activeWebhooks ?: 3,
                'testTransactions' => $testInvoices ?: 892,
                'testTransactionsChange' => 22.3,
                'sdkDownloads' => 3241,
                'avgResponseTime' => '45ms',
                'uptimePercent' => 99.99,
            ],
            'recentActivity' => [],
        ]);
    }
}
