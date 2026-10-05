<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function makeTask(User $assignee, User $assigner): Task
    {
        return Task::create([
            'title' => 'Test task',
            'assigned_to' => $assignee->id,
            'assigned_by' => $assigner->id,
            'due_date' => now()->addWeek(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);
    }

    public function test_manager_can_manage_a_team_members_task(): void
    {
        Role::create(['name' => 'manager']);
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $teamMember = User::factory()->create();
        $manager->teamMembers()->attach($teamMember->id);

        $task = $this->makeTask($teamMember, $manager);

        $this->assertTrue($manager->can('manage', $task));
    }

    public function test_manager_cannot_manage_a_strangers_task(): void
    {
        Role::create(['name' => 'manager']);
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $stranger = User::factory()->create();
        $otherManager = User::factory()->create();
        $task = $this->makeTask($stranger, $otherManager);

        $this->assertFalse($manager->can('manage', $task));
    }

    public function test_manager_cannot_assign_a_task_to_someone_outside_their_team(): void
    {
        Role::create(['name' => 'manager']);
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $outsider = User::factory()->create();

        $this->assertFalse($manager->can('assignTo', [Task::class, $outsider->id]));
    }

    public function test_employee_can_touch_their_own_task(): void
    {
        $employee = User::factory()->create();
        $assigner = User::factory()->create();
        $task = $this->makeTask($employee, $assigner);

        $this->assertTrue($employee->can('isAssignee', $task));
    }

    public function test_employee_cannot_touch_someone_elses_task(): void
    {
        $employee = User::factory()->create();
        $colleague = User::factory()->create();
        $assigner = User::factory()->create();
        $task = $this->makeTask($colleague, $assigner);

        $this->assertFalse($employee->can('isAssignee', $task));
    }

    public function test_manager_viewing_a_non_team_task_gets_a_404_not_a_403(): void
    {
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'employee']);

        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $stranger = User::factory()->create();
        $stranger->assignRole('employee');
        $otherManager = User::factory()->create();
        $task = $this->makeTask($stranger, $otherManager);

        $response = $this->actingAs($manager)->get(route('employee.assign-tasks.show', $task));

        $response->assertNotFound();
    }
}
