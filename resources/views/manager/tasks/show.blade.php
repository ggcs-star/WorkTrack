<x-task-detail-card
    :task="$task"
    :breadcrumb-items="['Dashboard' => route('employee.dashboard'), 'Team Tasks' => route('employee.assign-tasks.index'), $task->title => '']"
    :mark-completed-route="route('employee.assign-tasks.mark-completed', $task)"
/>
