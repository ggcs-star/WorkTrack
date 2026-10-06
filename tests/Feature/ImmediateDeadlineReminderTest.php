<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDeadlineApproaching;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ImmediateDeadlineReminderTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdminAndEmployee(): array
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();

        return [$admin, $employee];
    }

    public function test_creating_a_task_due_tomorrow_sends_an_immediate_day_reminder(): void
    {
        Notification::fake();
        [$admin, $employee] = $this->makeAdminAndEmployee();

        $task = Task::create([
            'title' => 'Submit report',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addDay(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Notification::assertSentTo(
            $employee,
            TaskDeadlineApproaching::class,
            fn ($notification) => $notification->task->is($task) && $notification->period === 'day'
        );
    }

    public function test_creating_a_task_due_in_exactly_seven_days_sends_an_immediate_week_reminder(): void
    {
        Notification::fake();
        [$admin, $employee] = $this->makeAdminAndEmployee();

        $task = Task::create([
            'title' => 'Prepare audit',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addDays(7),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Notification::assertSentTo(
            $employee,
            TaskDeadlineApproaching::class,
            fn ($notification) => $notification->task->is($task) && $notification->period === 'week'
        );
    }

    public function test_creating_a_task_due_in_three_days_sends_no_immediate_reminder(): void
    {
        Notification::fake();
        [$admin, $employee] = $this->makeAdminAndEmployee();

        Task::create([
            'title' => 'Plan onboarding',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addDays(3),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Notification::assertNotSentTo($employee, TaskDeadlineApproaching::class);
    }

    public function test_a_freshly_spawned_recurring_task_a_month_out_sends_no_immediate_reminder(): void
    {
        Notification::fake();
        [$admin, $employee] = $this->makeAdminAndEmployee();

        $task = Task::create([
            'title' => 'Pay electric bill',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addDay(), // qualifies for the immediate "day" reminder on its own creation
            'priority' => 'medium',
            'status' => 'pending',
            'is_recurring' => true,
        ]);

        Notification::fake(); // reset, we only care about what happens on completion from here

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        $next = Task::where('id', '!=', $task->id)->firstOrFail();
        $this->assertTrue($next->due_date->diffInDays(today()) > 7);

        Notification::assertNotSentTo($employee, TaskDeadlineApproaching::class);
    }
}
