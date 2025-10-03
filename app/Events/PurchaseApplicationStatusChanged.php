<?php

namespace App\Events;

use App\Models\PurchaseApplication;
use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public PurchaseApplication $application,
        public RentalApplicationStatus $oldStatus,
        public RentalApplicationStatus $newStatus
    ) {
        //
    }
}
