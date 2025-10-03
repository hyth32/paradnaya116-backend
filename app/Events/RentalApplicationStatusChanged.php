<?php

namespace App\Events;

use App\Models\RentalApplication;
use App\Enums\RentalApplication\RentalApplicationStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RentalApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public RentalApplication $application,
        public RentalApplicationStatus $oldStatus,
        public RentalApplicationStatus $newStatus
    ) {
        //
    }
}
