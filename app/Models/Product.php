<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
    }

    private function updateArchiveStatus(?Carbon $time): self
    {
        $this->update(['archived_at' => $time]);
        return $this;
    }

    public function archive(): self
    {
        return $this->updateArchiveStatus(now());
    }

    public function unarchive(): self
    {
        return $this->updateArchiveStatus(null);
    }

    public function getIsArchivedAttribute(): bool
    {
        return $this->archived_at !== null;
    }

    public function getIsDeletedAttribute(): bool
    {
        return $this->trashed();
    }
}
