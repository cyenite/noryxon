<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Get team members
        $team = $user->teamMembers()
            ->latest()
            ->get()
            ->map(function ($member) {
                return [
                    'id' => (string)$member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'role' => $member->role,
                    'lastActive' => $member->last_active_at ? $member->last_active_at->diffForHumans() : 'Never',
                    'avatar' => $member->avatar,
                ];
            });

        // Get recent audit logs
        $auditLogs = $user->auditLogs()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'ip' => $log->ip_address,
                    'date' => $log->created_at->format('M j, Y, g:i a'),
                    'metadata' => collect($log->metadata)
                        ->map(fn($v, $k) => "$k: $v")
                        ->join(', '),
                ];
            });

        return Inertia::render('Dashboard/Settings', [
            'teamMembers' => $team,
            'auditLogs' => $auditLogs,
            // Include user data directly for business profile (it's in auth.user, but handy here)
        ]);
    }
}
