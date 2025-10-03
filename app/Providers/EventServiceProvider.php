<?php

namespace App\Providers;

use App\Events\PurchaseApplicationStatusChanged;
use App\Events\RentalApplicationStatusChanged;
use App\Listeners\UpdateProductStock;
use App\Listeners\UpdateProductStockForPurchase;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RentalApplicationStatusChanged::class => [
            UpdateProductStock::class,
        ],
        PurchaseApplicationStatusChanged::class => [
            UpdateProductStockForPurchase::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
