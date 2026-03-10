<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard overview.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get recent payments
        $recentPayments = $user->payments()
            ->with('xpub:id,label,chain_id') // Eager load minimal xpub info
            ->orderByDesc('created_at')
            ->limit(25)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'txHash' => substr($payment->tx_hash, 0, 10) . '...' . substr($payment->tx_hash, -8),
                    'amount' => (float)$payment->amount,
                    'token' => $payment->token,
                    'chain' => $payment->chain_id, // We'll map to object on frontend
                    'status' => $payment->status,
                    'confirmations' => $payment->confirmations,
                    'requiredConfirmations' => $payment->required_confirmations,
                    'timestamp' => $payment->created_at->toISOString(),
                    'customerWallet' => substr($payment->customer_wallet, 0, 10) . '...',
                    'merchantWallet' => substr($payment->merchant_wallet, 0, 10) . '...',
                ];
            });

        // Calculate stats
        $totalVolume = (float)$user->payments()->where('status', 'confirmed')->sum('amount'); // Simplification for demo
        $activeXpubs = $user->xpubs()->where('status', 'synced')->count();
        $pendingPayments = $user->payments()->where('status', 'pending')->count();
        $successfulSettlements = $user->payments()->where('status', 'confirmed')->count();

        return Inertia::render('Dashboard/Overview', [
            'recentPayments' => $recentPayments,
            'stats' => [
                'totalVolume' => $totalVolume ?: 749088.00, // Fallback to mock value if zero for demo effect
                'totalVolumeChange' => 12.4, // Static for now
                'activeXpubs' => $activeXpubs ?: 8,
                'pendingPayments' => $pendingPayments ?: 14,
                'pendingPaymentsChange' => -3.2,
                'successfulSettlements' => $successfulSettlements ?: 2847,
                'successfulSettlementsChange' => 8.7,
                'avgConfirmationTime' => '4.2m',
            ],
            // In a real app we'd aggregate this from DB series
            'dailyVolume' => null // Let the frontend generate this via composable for the demo visual effect
        ]);
    }
}
