<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Support\TrendCalculator;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $completedCount = Task::where('status', 'completed')->count();
        $overdueCount = Task::overdue()->count();
        $totalCount = Task::count();
        $pendingCount = $totalCount - $completedCount - $overdueCount;

        return view('admin.dashboard', [
            'employeeCount' => User::role('employee')->count(),
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'overdueCount' => $overdueCount,
            'pendingCount' => $pendingCount,
            'totalTrend' => TrendCalculator::weekOverWeek(Task::query()),
            'completedTrend' => TrendCalculator::weekOverWeek(Task::where('status', 'completed'), 'completed_at'),
            'pendingTrend' => TrendCalculator::weekOverWeek(Task::where('status', '!=', 'completed')->where('due_date', '>=', today())),
            'overdueTrend' => TrendCalculator::weekOverWeek(Task::overdue()),
            'recentTasks' => Task::with(['assignee', 'assigner'])->latest()->take(5)->get(),
            'dueToday' => Task::with('assignee')->where('status', '!=', 'completed')
                ->whereDate('due_date', today())
                ->orderBy('due_date')
                ->take(10)
                ->get(),
            'dueWeek' => Task::with('assignee')->where('status', '!=', 'completed')
                ->whereBetween('due_date', [today(), now()->endOfWeek()])
                ->orderBy('due_date')
                ->take(10)
                ->get(),
            'dueMonth' => Task::with('assignee')->where('status', '!=', 'completed')
                ->whereBetween('due_date', [today(), now()->endOfMonth()])
                ->orderBy('due_date')
                ->take(10)
                ->get(),
        ]);
    }
}
