<x-task-detail-card
    :task="$task"
    :breadcrumb-items="['Dashboard' => route('admin.dashboard'), 'Tasks' => route('admin.tasks.index'), $task->title => '']"
    :mark-completed-route="route('admin.tasks.mark-completed', $task)"
/>
