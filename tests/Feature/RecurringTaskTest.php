<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecurringTaskTest extends TestCase
{
    use RefreshDatabase;

    private function makeTask(User $assignee, User $assigner, array $overrides = []): Task
    {
        return Task::create(array_merge([
            'title' => 'Pay electric bill',
            'assigned_to' => $assignee->id,
            'assigned_by' => $assigner->id,
            'due_date' => now()->startOfMonth()->addDays(9), // the 10th
            'priority' => 'medium',
            'status' => 'pending',
        ], $overrides));
    }

    public function test_completing_a_recurring_task_spawns_next_months_occurrence(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $task = $this->makeTask($employee, $admin, ['is_recurring' => true]);

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        $this->assertDatabaseCount('tasks', 2);

        $next = Task::where('id', '!=', $task->id)->first();
        $this->assertNotNull($next);
        $this->assertTrue($next->is_recurring);
        $this->assertSame('pending', $next->status);
        $this->assertSame($task->id, $next->parent_task_id);
        $this->assertSame($task->title, $next->title);
        $this->assertSame($task->due_date->copy()->addMonthNoOverflow()->toDateString(), $next->due_date->toDateString());
    }

    public function test_completing_a_one_time_task_does_not_spawn_anything(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $task = $this->makeTask($employee, $admin, ['is_recurring' => false]);

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_completing_a_recurring_task_several_months_late_catches_up_to_a_future_date(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        // Due 3 months ago, only just now marked complete.
        $task = $this->makeTask($employee, $admin, [
            'is_recurring' => true,
            'due_date' => now()->subMonths(3),
        ]);

        $task->update(['status' => 'completed', 'completed_at' => now()]);

        $next = Task::where('id', '!=', $task->id)->first();
        $this->assertNotNull($next);
        $this->assertTrue($next->due_date->isFuture() || $next->due_date->isToday());
    }

    public function test_marking_completed_twice_does_not_spawn_a_duplicate(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $task = $this->makeTask($employee, $admin, ['is_recurring' => true]);

        $task->update(['status' => 'completed', 'completed_at' => now()]);
        $this->assertDatabaseCount('tasks', 2);

        // Re-saving with status already 'completed' should not fire wasChanged('status') again,
        // and even if it did, the existence guard should prevent a second spawn.
        $task->update(['remarks' => 'paid via UPI']);
        $this->assertDatabaseCount('tasks', 2);
    }

    public function test_an_incomplete_recurring_task_past_its_due_date_shows_as_overdue(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $task = $this->makeTask($employee, $admin, [
            'is_recurring' => true,
            'due_date' => now()->subDays(2),
        ]);

        $this->assertTrue($task->fresh()->is_overdue);
        $this->assertTrue(Task::overdue()->whereKey($task->id)->exists());
    }

    public function test_series_root_id_is_shared_across_the_whole_chain(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $root = $this->makeTask($employee, $admin, ['is_recurring' => true]);
        $root->update(['status' => 'completed', 'completed_at' => now()]);
        $gen2 = Task::where('parent_task_id', $root->id)->firstOrFail();

        $this->assertSame($root->id, $gen2->series_root_id);

        $gen2->update(['status' => 'completed', 'completed_at' => now()]);
        $gen3 = Task::where('parent_task_id', $root->id)->where('id', '!=', $gen2->id)->first();

        $this->assertNotNull($gen3);
        $this->assertSame($root->id, $gen3->parent_task_id);
        $this->assertDatabaseCount('tasks', 3);
    }

    public function test_an_overdue_unfinished_recurring_task_still_spawns_the_next_occurrence(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $task = $this->makeTask($employee, $admin, [
            'is_recurring' => true,
            'due_date' => now()->subDays(5),
        ]);

        Artisan::call('tasks:spawn-overdue-recurring');

        $this->assertDatabaseCount('tasks', 2);

        $next = Task::where('id', '!=', $task->id)->firstOrFail();
        $this->assertSame('pending', $next->status);
        $this->assertSame($task->id, $next->parent_task_id);
        $this->assertTrue($next->due_date->isFuture());

        // The original stays exactly as it was — still unresolved, still overdue.
        $task->refresh();
        $this->assertSame('pending', $task->status);
        $this->assertTrue($task->is_overdue);
    }

    public function test_an_overdue_one_time_task_is_untouched_by_the_recurring_command(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $this->makeTask($employee, $admin, [
            'is_recurring' => false,
            'due_date' => now()->subDays(5),
        ]);

        Artisan::call('tasks:spawn-overdue-recurring');

        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_running_the_overdue_command_twice_does_not_double_spawn(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        $this->makeTask($employee, $admin, [
            'is_recurring' => true,
            'due_date' => now()->subDays(5),
        ]);

        Artisan::call('tasks:spawn-overdue-recurring');
        Artisan::call('tasks:spawn-overdue-recurring');

        $this->assertDatabaseCount('tasks', 2);
    }

    public function test_completed_recurring_tasks_are_ignored_by_the_overdue_command(): void
    {
        $admin = User::factory()->create();
        $employee = User::factory()->create();

        // Already completed via the normal path (so its successor already exists).
        $task = $this->makeTask($employee, $admin, [
            'is_recurring' => true,
            'due_date' => now()->subDays(5),
        ]);
        $task->update(['status' => 'completed', 'completed_at' => now()]);
        $this->assertDatabaseCount('tasks', 2);

        Artisan::call('tasks:spawn-overdue-recurring');

        // Still just the original (completed, excluded by status filter) + its one successor.
        $this->assertDatabaseCount('tasks', 2);
    }
}
