<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\TaskController as EmployeeTaskController;
use App\Http\Controllers\Manager\TaskController as ManagerTaskController;
use App\Http\Controllers\Manager\TeamController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskRedirectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    return redirect(Auth::user()->hasRole('admin') ? route('admin.dashboard') : route('employee.dashboard'));
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/employee-details', [ProfileController::class, 'updateEmployeeDetails'])->name('profile.update-employee-details');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    Route::get('/activity', [ActivityController::class, 'index'])->name('activity.index');

    Route::get('/tasks/{task}/open', [TaskRedirectController::class, 'show'])->name('tasks.open');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::resource('employees', EmployeeController::class);
    Route::patch('/employees/{employee}/update-role', [EmployeeController::class, 'updateRole'])->name('employees.update-role');
    Route::patch('/employees/{employee}/update-status', [EmployeeController::class, 'updateStatus'])->name('employees.update-status');
    Route::get('/employees/{employee}/team', [EmployeeController::class, 'team'])->name('employees.team');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::resource('tasks', AdminTaskController::class);
    Route::patch('/tasks/{task}/mark-completed', [AdminTaskController::class, 'markCompleted'])->name('tasks.mark-completed');
    Route::patch('/tasks/{task}/update-status', [AdminTaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::patch('/tasks/{task}/update-priority', [AdminTaskController::class, 'updatePriority'])->name('tasks.update-priority');
    Route::post('/tasks/{task}/comments', [AdminTaskController::class, 'storeComment'])->name('tasks.comments.store');
    Route::get('/reports', ReportsController::class)->name('reports');
    Route::get('/settings', SettingsController::class)->name('settings');
});

Route::middleware(['auth', 'not-admin'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', EmployeeDashboardController::class)->name('dashboard');
    Route::get('/tasks', [EmployeeTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [EmployeeTaskController::class, 'show'])->name('tasks.show');
    Route::patch('/tasks/{task}/update-status', [EmployeeTaskController::class, 'updateStatus'])->name('tasks.update-status');
    Route::post('/tasks/{task}/comments', [EmployeeTaskController::class, 'storeComment'])->name('tasks.comments.store');

    Route::middleware('role:manager|team leader')->group(function () {
        Route::get('/team', [TeamController::class, 'edit'])->name('team.edit');
        Route::put('/team', [TeamController::class, 'update'])->name('team.update');

        Route::resource('assign-tasks', ManagerTaskController::class)->parameters(['assign-tasks' => 'task']);
        Route::patch('/assign-tasks/{task}/mark-completed', [ManagerTaskController::class, 'markCompleted'])->name('assign-tasks.mark-completed');
        Route::patch('/assign-tasks/{task}/update-status', [ManagerTaskController::class, 'updateStatus'])->name('assign-tasks.update-status');
        Route::patch('/assign-tasks/{task}/update-priority', [ManagerTaskController::class, 'updatePriority'])->name('assign-tasks.update-priority');
        Route::post('/assign-tasks/{task}/comments', [ManagerTaskController::class, 'storeComment'])->name('assign-tasks.comments.store');
    });
});

require __DIR__.'/auth.php';
