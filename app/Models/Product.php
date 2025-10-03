<?php

namespace App\Models;

use App\Enums\Product\ProductStatus;
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
        'status',
    ];

    protected $casts = [
        'status' => ProductStatus::class,
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
        return $this->belongsToMany(RentalApplication::class, 'rental_application_products')->withPivot('quantity');
    }

    public function purchaseApplications(): BelongsToMany
    {
        return $this->belongsToMany(PurchaseApplication::class, 'purchase_application_products')->withPivot('quantity');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Active);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Archived);
    }

    public function scopeTrashed(Builder $query): Builder
    {
        return $query->where('status', ProductStatus::Trashed);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->where('quantity', '>', 0);
    }

    public function resolveRouteBinding($value, $field = null): ?Product
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }

    private function updateArchiveStatus(?Carbon $time): self
    {
        $this->update(['status' => $time ? ProductStatus::Archived : ProductStatus::Active]);
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
        return $this->status === ProductStatus::Archived;
    }

    public function isTrashed(): bool
    {
        return $this->status === ProductStatus::Trashed;
    }

    public function getAvailableQuantity()
    {
        $rentalReserved = $this->rentalApplications()
            ->where('status', 'active')
            ->sum('rental_application_products.quantity');
        
        return max(0, $this->quantity - $rentalReserved);
    }

    public function getRentalReservedQuantity()
    {
        return $this->rentalApplications()
            ->where('status', 'active')
            ->sum('rental_application_products.quantity');
    }

    public function getPurchaseReservedQuantity()
    {
        return $this->purchaseApplications()
            ->where('status', 'active')
            ->sum('purchase_application_products.quantity');
    }

    public function getTotalReservedQuantity()
    {
        return $this->getRentalReservedQuantity() + $this->getPurchaseReservedQuantity();
    }

    public function getAvailableForRental()
    {
        return max(0, $this->quantity - $this->getRentalReservedQuantity());
    }

    public function getAvailableForPurchase()
    {
        return max(0, $this->quantity - $this->getPurchaseReservedQuantity());
    }
}
