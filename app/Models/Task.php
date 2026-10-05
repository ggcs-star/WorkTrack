<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const PRIORITIES = ['low', 'medium', 'high'];

    public const STATUSES = ['pending', 'in_progress', 'dependency', 'need_clarification', 'completed'];

    public const PRIORITY_STYLES = [
        'low' => ['badge' => 'bg-success-50 text-success-700', 'select' => 'bg-success-50 text-success-700 border-success-500'],
        'medium' => ['badge' => 'bg-warning-50 text-warning-700', 'select' => 'bg-warning-50 text-warning-700 border-warning-500'],
        'high' => ['badge' => 'bg-danger-50 text-danger-700', 'select' => 'bg-danger-50 text-danger-700 border-danger-500'],
    ];

    public const STATUS_STYLES = [
        'pending' => ['badge' => 'bg-warning-50 text-warning-700 border border-warning-500', 'select' => 'bg-warning-50 text-warning-700 border-warning-500', 'label' => 'Pending'],
        'in_progress' => ['badge' => 'bg-sky-50 text-sky-700 border border-sky-500', 'select' => 'bg-sky-50 text-sky-700 border-sky-500', 'label' => 'In Progress'],
        'dependency' => ['badge' => 'bg-purple-50 text-purple-700 border border-purple-500', 'select' => 'bg-purple-50 text-purple-700 border-purple-500', 'label' => 'Dependency'],
        'need_clarification' => ['badge' => 'bg-indigo-50 text-indigo-700 border border-indigo-500', 'select' => 'bg-indigo-50 text-indigo-700 border-indigo-500', 'label' => 'Need Clarification'],
        'completed' => ['badge' => 'bg-success-50 text-success-700 border border-success-500', 'select' => 'bg-success-50 text-success-700 border-success-500', 'label' => 'Completed'],
        'overdue' => ['badge' => 'bg-danger-50 text-danger-700 border border-danger-500', 'select' => 'bg-danger-50 text-danger-700 border-danger-500', 'label' => 'Overdue'],
    ];

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'assigned_by',
        'depends_on_user_id',
        'due_date',
        'priority',
        'status',
        'remarks',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function dependsOnUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'depends_on_user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->oldest();
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'completed' && $this->due_date->isPast();
    }

    public function getEffectiveStatusAttribute(): string
    {
        return $this->is_overdue ? 'overdue' : $this->status;
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')->where('due_date', '<', today());
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeOutstanding(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')->where('due_date', '>=', today());
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')->whereDate('due_date', today());
    }

    public function scopeDueThisWeek(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')->whereBetween('due_date', [today(), now()->endOfWeek()]);
    }

    public function scopeDueThisMonth(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed')->whereBetween('due_date', [today(), now()->endOfMonth()]);
    }

    public function scopeForTeamOf(Builder $query, User $manager): Builder
    {
        return $query->whereIn('assigned_to', $manager->teamMemberIds());
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, fn (Builder $q) => $q->where('title', 'like', '%'.$term.'%'));
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return $query
            ->when($status === 'overdue', fn (Builder $q) => $q->overdue())
            ->when($status && $status !== 'overdue', fn (Builder $q) => $q->where('status', $status));
    }

    public function scopeFilterPriority(Builder $query, ?string $priority): Builder
    {
        return $query->when($priority, fn (Builder $q) => $q->where('priority', $priority));
    }

    public function scopeFilterAssignedTo(Builder $query, ?int $assignedTo): Builder
    {
        return $query->when($assignedTo, fn (Builder $q) => $q->where('assigned_to', $assignedTo));
    }
}
