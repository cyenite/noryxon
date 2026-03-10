<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Display the live monitor.
     */
    public function index(Request $request): Response
    {
        // For the live monitor, we'll send the initial batch of recent payments.
        // The frontend will then (in a real scenario) connect to a websocket or poll,
        // but for now we'll seed the initial state and let the frontend simulator run.
        $recentPayments = $request->user()->payments()
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => substr($payment->tx_hash, 0, 8), // Unique ID for frontend list transition
                    'txHash' => substr($payment->tx_hash, 0, 10) . '...' . substr($payment->tx_hash, -8),
                    'amount' => (float)$payment->amount,
                    'token' => $payment->token,
                    'chain' => $payment->chain_id, // We'll map to object on frontend
                    'status' => $payment->status,
                    'confirmations' => $payment->confirmations,
                    'requiredConfirmations' => $payment->required_confirmations,
                    'timestamp' => $payment->created_at->toISOString(),
                ];
            });

        return Inertia::render('Dashboard/LiveMonitor', [
            'initialPayments' => $recentPayments,
        ]);
    }
}
