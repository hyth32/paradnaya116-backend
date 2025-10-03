<?php

namespace App\Listeners;

use App\Events\RentalApplicationStatusChanged;
use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Support\Facades\Log;

class UpdateProductStock
{
    public function __construct()
    {
        //
    }

    public function handle(RentalApplicationStatusChanged $event): void
    {
        $application = $event->application;
        $oldStatus = $event->oldStatus;
        $newStatus = $event->newStatus;

        $application->load('products');

        foreach ($application->products as $product) {
            $quantity = $product->pivot->quantity;
            
            Log::info("Rental Application Status Changed", [
                'application_id' => $application->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'old_status' => $oldStatus->value,
                'new_status' => $newStatus->value,
            ]);

            // При активации заявки - резервируем товары
            if ($oldStatus !== RentalApplicationStatus::Active && $newStatus === RentalApplicationStatus::Active) {
                Log::info("Reserving products for rental", [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
            
            // При отмене или завершении - освобождаем товары
            if ($oldStatus === RentalApplicationStatus::Active && 
                in_array($newStatus, [RentalApplicationStatus::Canceled, RentalApplicationStatus::Completed])) {
                Log::info("Releasing products from rental", [
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
