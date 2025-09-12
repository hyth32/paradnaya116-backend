<?php

namespace App\Models;

use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class RentalApplication extends Model
{
    use SoftDeletes, AsSource, Filterable;

    protected $fillable = [
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'deposit',
        'comment',
        'total_price',
        'start_date',
        'end_date',
        'approved_at',
        'canceled_at',
    ];

    protected $casts = [
        'status' => RentalApplicationStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, RentalApplicationProduct::class);
    }

    public function scopeStatus(Builder $query, RentalApplicationStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->status(RentalApplicationStatus::New);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->status(RentalApplicationStatus::Active);
    }

    public function scopeCanceled(Builder $query): Builder
    {
        return $query->status(RentalApplicationStatus::Canceled);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->status(RentalApplicationStatus::Completed);
    }

    public function hasStatus(RentalApplicationStatus $status): bool
    {
        return $this->status == $status;
    }

    public function isNew(): bool
    {
        return $this->hasStatus(RentalApplicationStatus::New);
    }

    public function isActive(): bool
    {
        return $this->hasStatus(RentalApplicationStatus::Active);
    }

    public function isCanceled(): bool
    {
        return $this->hasStatus(RentalApplicationStatus::Canceled);
    }

    public function isCompleted(): bool
    {
        return $this->hasStatus(RentalApplicationStatus::Completed);
    }

    public function setStatus(RentalApplicationStatus $status): self
    {
        $this->update(['status' => $status]);
        return $this->refresh();
    }
}
