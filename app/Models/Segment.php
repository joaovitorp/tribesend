<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Segment extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(Subscriber::class, 'segment_subscriber')
            ->withTimestamps();
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_segment')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function includedInCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_segment')
            ->wherePivot('type', 'include')
            ->withTimestamps();
    }

    public function excludedFromCampaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_segment')
            ->wherePivot('type', 'exclude')
            ->withTimestamps();
    }
}
