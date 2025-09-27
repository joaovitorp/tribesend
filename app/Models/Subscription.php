<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasUuid;

    protected $fillable = [
        'team_id',
        'plan_id',
        'status',
        'provider',
        'provider_subscription_id',
        'provider_data',
        'current_period_start',
        'current_period_end',
        'trial_ends_at',
        'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'provider_data' => 'array',
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'trial_ends_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isOnTrial(): bool
    {
        return $this->on_trial && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function trialEndsToday(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isToday();
    }

    public function trialExpired(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    public function daysLeftInTrial(): int
    {
        if (! $this->isOnTrial()) {
            return 0;
        }

        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }
}
