<?php

namespace App\Models;

use App\Enums\Promotion\PromotionStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Promotion extends Model
{
    use SoftDeletes, AsSource, Filterable;

    protected $fillable = [
        'name',
        'image',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => PromotionStatus::class,
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', PromotionStatus::Active);
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', PromotionStatus::Expired);
    }

    public function scopeTrashed(Builder $query): Builder
    {
        return $query->onlyTrashed();
    }

    public function isActive(): bool
    {
        return $this->status === PromotionStatus::Active;
    }

    public function isExpired(): bool
    {
        return $this->status === PromotionStatus::Expired;
    }

    public function isTrashed(): bool
    {
        return $this->trashed();
    }

    public function updateStatus(): void
    {
        $now = Carbon::now();
        
        if ($this->end_date < $now && $this->status === PromotionStatus::Active) {
            $this->update(['status' => PromotionStatus::Expired]);
        }
    }

    public function getDurationAttribute(): string
    {
        return $this->start_date->format('d.m.Y') . ' - ' . $this->end_date->format('d.m.Y');
    }
}
