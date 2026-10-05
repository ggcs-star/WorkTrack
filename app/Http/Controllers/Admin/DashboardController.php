<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Support\TaskDueBuckets;
use App\Support\TrendCalculator;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $completedCount = Task::completed()->count();
        $overdueCount = Task::overdue()->count();
        $totalCount = Task::count();
        $pendingCount = $totalCount - $completedCount - $overdueCount;

        $dueBuckets = TaskDueBuckets::from(Task::query());

        return view('admin.dashboard', [
            'employeeCount' => User::role('employee')->count(),
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'overdueCount' => $overdueCount,
            'pendingCount' => $pendingCount,
            'totalTrend' => TrendCalculator::weekOverWeek(Task::query()),
            'completedTrend' => TrendCalculator::weekOverWeek(Task::completed(), 'completed_at'),
            'pendingTrend' => TrendCalculator::weekOverWeek(Task::outstanding()),
            'overdueTrend' => TrendCalculator::weekOverWeek(Task::overdue()),
            'recentTasks' => Task::with(['assignee', 'assigner'])->latest()->take(5)->get(),
            'recentComments' => TaskComment::with(['user.profile', 'task'])->visibleTo(auth()->user())->latest()->take(5)->get(),
            'dueToday' => $dueBuckets['today'],
            'dueWeek' => $dueBuckets['week'],
            'dueMonth' => $dueBuckets['month'],
        ]);
    }
}
