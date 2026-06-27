// app/Http/Controllers/Api/AnalyticsController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Get overview analytics
     */
    public function overview(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->subDays(30));
        $dateTo = $request->get('date_to', now());

        $stats = [
            'tickets' => [
                'total' => Ticket::count(),
                'open' => Ticket::open()->count(),
                'in_progress' => Ticket::inProgress()->count(),
                'resolved' => Ticket::resolved()->count(),
                'closed' => Ticket::closed()->count(),
            ],
            'tickets_by_priority' => [
                'critical' => Ticket::priority('critical')->count(),
                'high' => Ticket::priority('high')->count(),
                'medium' => Ticket::priority('medium')->count(),
                'low' => Ticket::priority('low')->count(),
            ],
            'tickets_by_category' => Ticket::select('category_id', DB::raw('count(*) as total'))
                ->groupBy('category_id')
                ->with('category')
                ->get(),
            'performance' => [
                'avg_response_time' => Ticket::whereNotNull('response_time')->avg('response_time'),
                'avg_resolution_time' => Ticket::whereNotNull('resolution_time')->avg('resolution_time'),
                'tickets_per_day' => Ticket::whereBetween('created_at', [$dateFrom, $dateTo])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->get(),
            ],
            'agents' => [
                'total' => User::agents()->count(),
                'active' => User::agents()->active()->count(),
                'performance' => User::agents()
                    ->withCount(['assignedTickets' => function ($query) {
                        $query->where('status', 'resolved');
                    }])
                    ->get()
            ],
            'activity' => ActivityLog::whereBetween('created_at', [$dateFrom, $dateTo])
                ->select('action', DB::raw('count(*) as total'))
                ->groupBy('action')
                ->get()
        ];

        return response()->json($stats);
    }

    /**
     * Generate report
     */
    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:daily,weekly,monthly',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        // Generate report based on type
        $report = $this->generateReportData($validated);

        return response()->json([
            'report' => $report,
            'generated_at' => now()
        ]);
    }

    /**
     * Agent performance analytics
     */
    public function agentPerformance(Request $request)
    {
        $agents = User::agents()->with(['assignedTickets' => function ($query) {
            $query->whereBetween('created_at', [now()->subDays(30), now()]);
        }])->get();

        $performance = $agents->map(function ($agent) {
            $tickets = $agent->assignedTickets;
            $resolved = $tickets->where('status', 'resolved');
            
            return [
                'agent' => [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'email' => $agent->email,
                ],
                'stats' => [
                    'total_tickets' => $tickets->count(),
                    'resolved' => $resolved->count(),
                    'pending' => $tickets->whereIn('status', ['open', 'in_progress'])->count(),
                    'avg_resolution_time' => $resolved->avg('resolution_time') ?? 0,
                    'resolution_rate' => $tickets->count() > 0 
                        ? round(($resolved->count() / $tickets->count()) * 100, 2) 
                        : 0,
                ]
            ];
        });

        return response()->json($performance);
    }

    private function generateReportData($filters)
    {
        // Implementation for report generation
        return [
            'filters' => $filters,
            'summary' => [
                'total_tickets' => Ticket::whereBetween('created_at', [$filters['date_from'], $filters['date_to']])->count(),
                'resolved_tickets' => Ticket::whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
                    ->where('status', 'resolved')->count(),
                'avg_resolution_time' => Ticket::whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
                    ->whereNotNull('resolution_time')->avg('resolution_time') ?? 0,
            ],
            'tickets_by_status' => Ticket::whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),
            'tickets_by_priority' => Ticket::whereBetween('created_at', [$filters['date_from'], $filters['date_to']])
                ->select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->get(),
        ];
    }
}