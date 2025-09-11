<?php

namespace App\Models;

use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RentalApplication extends Model
{
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

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, RentalApplicationProduct::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', RentalApplicationStatus::New->value);
    }

    public function scopeActive($query)
    {
        return $query->where('status', RentalApplicationStatus::Active->value);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', RentalApplicationStatus::Canceled->value);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', RentalApplicationStatus::Completed->value);
    }
}
