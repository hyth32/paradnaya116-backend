<?php

namespace App\Listeners;

use App\Events\PurchaseApplicationStatusChanged;
use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateProductStockForPurchase
{
    public function __construct()
    {
        //
    }

    public function handle(PurchaseApplicationStatusChanged $event): void
    {
        $application = $event->application;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        $application->load('products');

        foreach ($application->products as $product) {
            $quantity = $product->pivot->quantity;
            
            Log::info("Purchase Application Status Changed", [
                'application_id' => $application->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'old_status' => $oldStatus->value,
                'new_status' => $newStatus->value,
            ]);

            // При активации заявки - резервируем товары
            if ($oldStatus !== RentalApplicationStatus::Active && $newStatus === RentalApplicationStatus::Active) {
                Log::info("Reserving products for purchase", [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
            
            // При завершении - уменьшаем общее количество товара
            if ($oldStatus === RentalApplicationStatus::Active && $newStatus === RentalApplicationStatus::Completed) {
                $product->quantity = max(0, $product->quantity - $quantity);
                $product->save();
                
                Log::info("Reduced product quantity after purchase", [
                    'product_id' => $product->id,
                    'quantity_reduced' => $quantity,
                    'new_quantity' => $product->quantity,
                ]);
            }
            
            // При отмене - освобождаем товары
            if ($oldStatus === RentalApplicationStatus::Active && $newStatus === RentalApplicationStatus::Canceled) {
                Log::info("Releasing products from purchase", [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
