<x-task-detail-card
    :task="$task"
    :breadcrumb-items="['Dashboard' => route('employee.dashboard'), 'My Tasks' => route('employee.tasks.index'), $task->title => '']"
    :show-assignee="false"
    :status-update-route="route('employee.tasks.update-status', $task)"
    :colleagues="$colleagues"
/>
