<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskRedirectTest extends TestCase
{
    use RefreshDatabase;

    private function makeTask(User $assignee, User $assigner): Task
    {
        return Task::create([
            'title' => 'Sofa cleaning',
            'assigned_to' => $assignee->id,
            'assigned_by' => $assigner->id,
            'due_date' => now()->addWeek(),
            'priority' => 'medium',
            'status' => 'pending',
        ]);
    }

    public function test_admin_clicking_the_email_link_lands_on_the_admin_task_page(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();
        $task = $this->makeTask($employee, $admin);

        $this->actingAs($admin)
            ->get(route('tasks.open', $task))
            ->assertRedirect(route('admin.tasks.show', $task));
    }

    public function test_the_assignee_clicking_the_email_link_lands_on_their_own_task_page(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();
        $task = $this->makeTask($employee, $admin);

        $this->actingAs($employee)
            ->get(route('tasks.open', $task))
            ->assertRedirect(route('employee.tasks.show', $task));
    }

    public function test_the_assignees_manager_clicking_the_email_link_lands_on_the_team_task_page(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $manager = User::factory()->create();
        $manager->assignRole('manager');
        $employee = User::factory()->create();
        $manager->teamMembers()->attach($employee->id);
        $task = $this->makeTask($employee, $admin);

        $this->actingAs($manager)
            ->get(route('tasks.open', $task))
            ->assertRedirect(route('employee.assign-tasks.show', $task));
    }

    public function test_an_unrelated_employee_clicking_the_email_link_gets_403(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();
        $stranger = User::factory()->create();
        $task = $this->makeTask($employee, $admin);

        $this->actingAs($stranger)
            ->get(route('tasks.open', $task))
            ->assertForbidden();
    }

    public function test_a_logged_out_visitor_is_sent_to_login_then_back_to_the_task_after_logging_in(): void
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $employee = User::factory()->create();
        $task = $this->makeTask($employee, $admin);

        // Not logged in: redirected to login, intended URL remembered.
        $this->get(route('tasks.open', $task))->assertRedirect(route('login'));

        // Log in as the assignee: Laravel's redirect()->intended() sends them
        // straight back to the smart URL, which then dispatches correctly.
        $response = $this->post(route('login'), [
            'email' => $employee->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('tasks.open', $task));

        // Follow through manually: now authenticated, the smart URL dispatches correctly.
        $this->get(route('tasks.open', $task))->assertRedirect(route('employee.tasks.show', $task));
    }
}
