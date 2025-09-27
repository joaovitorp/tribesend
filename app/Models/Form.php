<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'team_id',
        'name',
        'hash',
        'subscriber_groups',
        'fields',
        'referral',
        'content',
        'is_active',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'subscriber_groups' => 'array',
            'fields' => 'array',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Form $form) {
            if (empty($form->hash)) {
                $form->hash = Str::random(32);
            }
        });
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function subscriberGroupModels(): BelongsToMany
    {
        return $this->belongsToMany(
            SubscriberGroup::class,
            'form_subscriber_group',
            'form_id',
            'subscriber_group_id'
        );
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeByHash($query, string $hash)
    {
        return $query->where('hash', $hash);
    }
}
