<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Product extends Model
{
    use SoftDeletes, AsSource, Filterable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'archived_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',  
        'updated_at' => 'datetime',  
        'archived_at' => 'datetime',  
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function rentalApplications(): BelongsToMany
    {
        return $this->belongsToMany(RentalApplication::class, RentalApplicationProduct::class)->withPivot('quantity');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereNotNull('archived_at');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->where('quantity', '>', 0);
    }

    public function resolveRouteBinding($value, $field = null): ?Product
    {
        return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }

    private function updateArchiveStatus(?Carbon $time): self
    {
        $this->update(['archived_at' => $time]);
        return $this->refresh();
    }

    public function archive(): self
    {
        return $this->updateArchiveStatus(now());
    }

    public function unarchive(): self
    {
        return $this->updateArchiveStatus(null);
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    public function getAvailableQuantity()
    {
        $pivot = 'rental_application_products';
        $reserved = $this->rentalApplications()->active()->sum("{$pivot}.quantity");
        
        return max(0, $this->quantity - $reserved);
    }
}
