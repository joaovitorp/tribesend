<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignSend extends Model
{
    protected $fillable = [
        'campaign_id',
        'subscriber_id',
        'status',
        'sent_at',
        'opened_at',
        'clicked_at',
        'bounced_at',
        'complained_at',
        'bounce_reason',
        'tracking_data',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'opened_at' => 'datetime',
            'clicked_at' => 'datetime',
            'bounced_at' => 'datetime',
            'complained_at' => 'datetime',
            'tracking_data' => 'array',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isOpened(): bool
    {
        return ! is_null($this->opened_at);
    }

    public function isClicked(): bool
    {
        return ! is_null($this->clicked_at);
    }

    public function isBounced(): bool
    {
        return ! is_null($this->bounced_at);
    }
}
