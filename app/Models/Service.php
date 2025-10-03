<?php

namespace App\Models;

use App\Enums\Service\ServiceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Service extends Model
{
    use SoftDeletes, AsSource, Filterable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => ServiceStatus::class,
        'price' => 'decimal:2',
    ];

    public function serviceApplications(): BelongsToMany
    {
        return $this->belongsToMany(ServiceApplication::class, 'service_application_services');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ServiceStatus::Active);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', ServiceStatus::Archived);
    }

    public function scopeTrashed(Builder $query): Builder
    {
        return $query->where('status', ServiceStatus::Trashed);
    }

    public function isActive(): bool
    {
        return $this->status === ServiceStatus::Active;
    }

    public function isArchived(): bool
    {
        return $this->status === ServiceStatus::Archived;
    }

    public function isTrashed(): bool
    {
        return $this->status === ServiceStatus::Trashed;
    }
}
