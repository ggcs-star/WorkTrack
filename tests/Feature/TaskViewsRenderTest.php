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
            'is_recurring' => true,
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

        // Admin: filter form + shared table + detail card (mark-completed variant), with the Monthly badge
        $this->actingAs($admin)->get(route('admin.tasks.index'))->assertOk()->assertSee('Monthly');
        $this->actingAs($admin)->get(route('admin.tasks.show', $adminTask))->assertOk()->assertSee('Monthly');
        $this->actingAs($admin)->get(route('admin.tasks.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.tasks.edit', $adminTask))->assertOk();

        // Manager: filter form + shared table + detail card, team-scoped (sees adminTask too, same team)
        $this->actingAs($manager)->get(route('employee.assign-tasks.index'))->assertOk()->assertSee('Monthly');
        $this->actingAs($manager)->get(route('employee.assign-tasks.show', $managerTask))->assertOk();
        $this->actingAs($manager)->get(route('employee.assign-tasks.create'))->assertOk();
        $this->actingAs($manager)->get(route('employee.assign-tasks.edit', $managerTask))->assertOk();

        // Employee: distinct table + detail card (status-select variant), with the Monthly badge
        $this->actingAs($employee)->get(route('employee.tasks.index'))->assertOk()->assertSee('Monthly');
        $this->actingAs($employee)->get(route('employee.tasks.show', $adminTask))->assertOk()->assertSee('Monthly');

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
            ->assertSee('Working on this now.')
            ->assertSee('My Tasks Due');

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

    public function test_task_index_search_matches_assignee_name_and_due_date_range_filters(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $priya = User::factory()->create(['name' => 'Priya Singh']);
        $priya->assignRole('employee');

        $karan = User::factory()->create(['name' => 'Karan Verma']);
        $karan->assignRole('employee');

        $priyaTask = Task::create([
            'title' => 'Conduct Interview',
            'assigned_to' => $priya->id,
            'assigned_by' => $admin->id,
            'due_date' => '2026-10-15',
            'priority' => 'high',
            'status' => 'pending',
        ]);

        $karanTask = Task::create([
            'title' => 'Update Handbook',
            'assigned_to' => $karan->id,
            'assigned_by' => $admin->id,
            'due_date' => '2026-11-20',
            'priority' => 'low',
            'status' => 'pending',
        ]);

        // Searching by assignee name finds Priya's task, not Karan's.
        $this->actingAs($admin)
            ->get(route('admin.tasks.index', ['search' => 'Priya']))
            ->assertOk()
            ->assertSee('Conduct Interview')
            ->assertDontSee('Update Handbook');

        // Searching by task title still works.
        $this->actingAs($admin)
            ->get(route('admin.tasks.index', ['search' => 'Handbook']))
            ->assertOk()
            ->assertSee('Update Handbook')
            ->assertDontSee('Conduct Interview');

        // Due date range limited to October 2026 finds only Priya's task.
        $this->actingAs($admin)
            ->get(route('admin.tasks.index', ['due_from' => '2026-10-01', 'due_to' => '2026-10-31']))
            ->assertOk()
            ->assertSee('Conduct Interview')
            ->assertDontSee('Update Handbook');
    }
}
