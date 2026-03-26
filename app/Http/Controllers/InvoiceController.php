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
                    'purpose' => $invoice->purpose,
                    'paymentLink' => $invoice->payment_link,
                    'customerEmail' => $invoice->customer_email,
                    'createdAt' => $invoice->created_at->toISOString(),
                    'expiresAt' => $invoice->expires_at ? $invoice->expires_at->toISOString() : null,
                    'paidAt' => $invoice->paid_at ? $invoice->paid_at->toISOString() : null,
                    'txHash' => $invoice->status === 'paid' ? '0x' . substr(md5($invoice->id . $invoice->invoice_number), 0, 40) : null,
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
            'purpose' => 'nullable|string|max:100',
        ]);

        $request->user()->invoices()->create([
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'customer_email' => $validated['customer_email'] ?? null,
            'memo' => $validated['memo'] ?? null,
            'purpose' => $validated['purpose'] ?? null,
            'invoice_number' => 'INV-' . mt_rand(10000, 99999),
            'payment_link' => 'pay.noryxon.com/' . Str::random(12),
            'status' => 'draft',
        ]);

        return back()->with('success', 'Invoice created successfully.');
    }
}
