<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskViewsRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_refactored_task_and_notification_pages_render(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'employee']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $employee = User::factory()->create();
        $employee->assignRole('employee');
        $manager->teamMembers()->attach($employee->id);

        $adminTask = Task::create([
            'title' => 'Admin task',
            'assigned_to' => $employee->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addWeek(),
            'priority' => 'high',
            'status' => 'pending',
        ]);

        $managerTask = Task::create([
            'title' => 'Manager task',
            'assigned_to' => $employee->id,
            'assigned_by' => $manager->id,
            'due_date' => now()->subDay(),
            'priority' => 'low',
            'status' => 'dependency',
            'depends_on_user_id' => $manager->id,
        ]);

        // Admin: filter form + shared table + detail card (mark-completed variant)
        $this->actingAs($admin)->get(route('admin.tasks.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.tasks.show', $adminTask))->assertOk();
        $this->actingAs($admin)->get(route('admin.tasks.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.tasks.edit', $adminTask))->assertOk();

        // Manager: filter form + shared table + detail card, team-scoped
        $this->actingAs($manager)->get(route('employee.assign-tasks.index'))->assertOk();
        $this->actingAs($manager)->get(route('employee.assign-tasks.show', $managerTask))->assertOk();
        $this->actingAs($manager)->get(route('employee.assign-tasks.create'))->assertOk();
        $this->actingAs($manager)->get(route('employee.assign-tasks.edit', $managerTask))->assertOk();

        // Employee: distinct table + detail card (status-select variant)
        $this->actingAs($employee)->get(route('employee.tasks.index'))->assertOk();
        $this->actingAs($employee)->get(route('employee.tasks.show', $adminTask))->assertOk();

        // Notifications: both dropdown (via layout) and full index page use x-notification-item
        $this->actingAs($employee)->get(route('notifications.index'))->assertOk();

        // A task that does NOT involve $employee at all, to test the activity scope boundary.
        $unrelatedTask = Task::create([
            'title' => 'Unrelated task',
            'assigned_to' => $manager->id,
            'assigned_by' => $admin->id,
            'due_date' => now()->addWeek(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        // Dashboards exercise TaskDueBuckets and the recent-activity-feed component
        $adminTask->comments()->create(['user_id' => $employee->id, 'body' => 'Working on this now.']);
        $managerTask->comments()->create(['user_id' => $manager->id, 'body' => 'Still waiting on the dependency.']);
        $unrelatedTask->comments()->create(['user_id' => $admin->id, 'body' => 'This has nothing to do with the employee.']);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Recent Activity')
            ->assertSee('Working on this now.');

        $this->actingAs($manager)->get(route('employee.dashboard'))
            ->assertOk()
            ->assertSee('Recent Activity')
            ->assertSee('Still waiting on the dependency.');

        $this->actingAs($employee)->get(route('employee.dashboard'))
            ->assertOk()
            ->assertSee('Recent Activity')
            ->assertSee('Working on this now.');

        // Full activity page: admin sees everything, others see only their own scope
        $this->actingAs($admin)->get(route('activity.index'))
            ->assertOk()
            ->assertSee('Working on this now.')
            ->assertSee('Still waiting on the dependency.')
            ->assertSee('This has nothing to do with the employee.');

        $this->actingAs($employee)->get(route('activity.index'))
            ->assertOk()
            ->assertSee('Working on this now.')
            ->assertSee('Still waiting on the dependency.')
            ->assertDontSee('This has nothing to do with the employee.');
    }
}
