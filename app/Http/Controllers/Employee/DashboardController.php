<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Support\TrendCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->id;
        $base = Task::where('assigned_to', $userId);

        $completedCount = (clone $base)->where('status', 'completed')->count();
        $overdueCount = (clone $base)->overdue()->count();
        $totalCount = (clone $base)->count();
        $pendingCount = $totalCount - $completedCount - $overdueCount;

        return view('employee.dashboard', [
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'overdueCount' => $overdueCount,
            'pendingCount' => $pendingCount,
            'totalTrend' => TrendCalculator::weekOverWeek(clone $base),
            'completedTrend' => TrendCalculator::weekOverWeek((clone $base)->where('status', 'completed'), 'completed_at'),
            'pendingTrend' => TrendCalculator::weekOverWeek((clone $base)->where('status', '!=', 'completed')->where('due_date', '>=', today())),
            'overdueTrend' => TrendCalculator::weekOverWeek((clone $base)->overdue()),
            'upcomingTasks' => (clone $base)
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderBy('due_date')
                ->take(5)
                ->get(),
        ]);
    }
}
