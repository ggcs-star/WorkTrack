<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Support\TaskDueBuckets;
use App\Support\TrendCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $base = Task::where('assigned_to', $request->user()->id);

        $completedCount = (clone $base)->completed()->count();
        $overdueCount = (clone $base)->overdue()->count();
        $totalCount = (clone $base)->count();
        $pendingCount = $totalCount - $completedCount - $overdueCount;

        $dueBuckets = TaskDueBuckets::from(clone $base);

        $data = [
            'totalCount' => $totalCount,
            'completedCount' => $completedCount,
            'overdueCount' => $overdueCount,
            'pendingCount' => $pendingCount,
            'totalTrend' => TrendCalculator::weekOverWeek(clone $base),
            'completedTrend' => TrendCalculator::weekOverWeek((clone $base)->completed(), 'completed_at'),
            'pendingTrend' => TrendCalculator::weekOverWeek((clone $base)->outstanding()),
            'overdueTrend' => TrendCalculator::weekOverWeek((clone $base)->overdue()),
            'upcomingTasks' => (clone $base)
                ->whereIn('status', ['pending', 'in_progress'])
                ->orderBy('due_date')
                ->take(5)
                ->get(),
            'recentComments' => TaskComment::with(['user.profile', 'task'])
                ->visibleTo($request->user())
                ->latest()
                ->take(5)
                ->get(),
            'dueToday' => $dueBuckets['today'],
            'dueWeek' => $dueBuckets['week'],
            'dueMonth' => $dueBuckets['month'],
            'isManager' => false,
        ];

        if ($request->user()->hasAnyRole(['manager', 'team leader'])) {
            $teamIds = $request->user()->teamMemberIds();
            $teamBase = Task::whereIn('assigned_to', $teamIds);

            $teamCompletedCount = (clone $teamBase)->completed()->count();
            $teamOverdueCount = (clone $teamBase)->overdue()->count();
            $teamTotalCount = (clone $teamBase)->count();
            $teamPendingCount = $teamTotalCount - $teamCompletedCount - $teamOverdueCount;

            $teamDueBuckets = TaskDueBuckets::from($teamBase);

            $data['isManager'] = true;
            $data['teamCount'] = count($teamIds);
            $data['teamTotalCount'] = $teamTotalCount;
            $data['teamCompletedCount'] = $teamCompletedCount;
            $data['teamPendingCount'] = $teamPendingCount;
            $data['teamOverdueCount'] = $teamOverdueCount;
            $data['teamDueToday'] = $teamDueBuckets['today'];
            $data['teamDueWeek'] = $teamDueBuckets['week'];
            $data['teamDueMonth'] = $teamDueBuckets['month'];
        }

        return view('employee.dashboard', $data);
    }
}
