<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskDueDateValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_a_task_with_a_past_due_date(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.tasks.store'), [
            'title' => 'Backdated task',
            'assigned_to' => $employee->id,
            'due_date' => now()->subDay()->format('Y-m-d'),
            'priority' => 'medium',
            'is_recurring' => 0,
        ]);

        $response->assertSessionHasErrors('due_date');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_admin_can_create_a_task_due_today(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.tasks.store'), [
            'title' => 'Due today task',
            'assigned_to' => $employee->id,
            'due_date' => now()->format('Y-m-d'),
            'priority' => 'medium',
            'is_recurring' => 0,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_editing_an_already_overdue_task_without_changing_its_date_still_works(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();

        $task = Task::create([
            'title' => 'Overdue task',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->subWeek(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.tasks.update', $task), [
            'title' => 'Overdue task (updated remarks)',
            'assigned_to' => $employee->id,
            'due_date' => $task->due_date->format('Y-m-d'), // unchanged, still in the past
            'priority' => 'high',
            'is_recurring' => 0,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertSame('Overdue task (updated remarks)', $task->fresh()->title);
    }
}
