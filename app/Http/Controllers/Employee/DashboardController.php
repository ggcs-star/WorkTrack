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

        $data = [
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
            'isManager' => false,
        ];

        if ($request->user()->hasAnyRole(['manager', 'team leader'])) {
            $teamIds = $request->user()->teamMembers()->pluck('users.id')->toArray();
            $teamBase = Task::whereIn('assigned_to', $teamIds);

            $teamCompletedCount = (clone $teamBase)->where('status', 'completed')->count();
            $teamOverdueCount = (clone $teamBase)->overdue()->count();
            $teamTotalCount = (clone $teamBase)->count();
            $teamPendingCount = $teamTotalCount - $teamCompletedCount - $teamOverdueCount;

            $data['isManager'] = true;
            $data['teamCount'] = count($teamIds);
            $data['teamTotalCount'] = $teamTotalCount;
            $data['teamCompletedCount'] = $teamCompletedCount;
            $data['teamPendingCount'] = $teamPendingCount;
            $data['teamOverdueCount'] = $teamOverdueCount;
            $data['teamDueToday'] = (clone $teamBase)->with('assignee')->where('status', '!=', 'completed')
                ->whereDate('due_date', today())
                ->orderBy('due_date')
                ->take(10)
                ->get();
            $data['teamDueWeek'] = (clone $teamBase)->with('assignee')->where('status', '!=', 'completed')
                ->whereBetween('due_date', [today(), now()->endOfWeek()])
                ->orderBy('due_date')
                ->take(10)
                ->get();
            $data['teamDueMonth'] = (clone $teamBase)->with('assignee')->where('status', '!=', 'completed')
                ->whereBetween('due_date', [today(), now()->endOfMonth()])
                ->orderBy('due_date')
                ->take(10)
                ->get();
        }

        return view('employee.dashboard', $data);
    }
}
