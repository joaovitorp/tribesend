<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
    use HasUuid;

    protected $fillable = [
        'team_id',
        'email',
        'first_name',
        'last_name',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function subscriberGroups(): BelongsToMany
    {
        return $this->belongsToMany(SubscriberGroup::class, 'subscriber_subscriber_group')
            ->withTimestamps();
    }

    public function campaignSends(): HasMany
    {
        return $this->hasMany(CampaignSend::class);
    }

    public function getFullNameAttribute(): ?string
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name.' '.$this->last_name);
        }

        return null;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
