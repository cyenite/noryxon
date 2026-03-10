<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $invoices = $user->invoices()
            ->latest()
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoiceNumber' => $invoice->invoice_number,
                    'amount' => (float)$invoice->amount,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status,
                    'memo' => $invoice->memo,
                    'paymentLink' => $invoice->payment_link,
                    'customerEmail' => $invoice->customer_email,
                    'createdAt' => $invoice->created_at->toISOString(),
                    'expiresAt' => $invoice->expires_at ? $invoice->expires_at->toISOString() : null,
                    'paidAt' => $invoice->paid_at ? $invoice->paid_at->toISOString() : null,
                ];
            });

        return Inertia::render('Dashboard/Invoices', [
            'initialInvoices' => $invoices,
        ]);
    }

    /**
     * Store a newly created invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:USDC,USDT,BTC,ETH,SOL,MATIC',
            'customer_email' => 'nullable|email|max:255',
            'memo' => 'nullable|string|max:500',
        ]);

        $request->user()->invoices()->create([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'customer_email' => $validated['customer_email'],
            'memo' => $validated['memo'],
            'invoice_number' => 'INV-' . mt_rand(10000, 99999), // Simple generator
            'payment_link' => 'pay.noryxon.com/' . Str::random(12),
            'status' => 'draft',
        ]);

        return back()->with('success', 'Invoice created successfully.');
    }
}
