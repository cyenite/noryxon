<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics page.
     */
    public function index(Request $request): Response
    {
        // In a real application, these would be complex aggregation queries across
        // the payments table grouped by dates, tokens, and chains.
        // For now, we'll rely on the frontend composable generators for the complex
        // chart shapes but provide the entry point here.
        
        return Inertia::render('Dashboard/Analytics', [
            // We pass null to signal the frontend to use its mock generator for the complex charts
            // until we build out the heavy aggregation SQL queries.
            'serverProvidedData' => false,
        ]);
    }
}
