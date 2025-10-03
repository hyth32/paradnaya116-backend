<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use App\Enums\Promotion\PromotionStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdatePromotionStatuses extends Command
{
    protected $signature = 'promotions:update-statuses';

    protected $description = 'Обновляет статусы акций: переводит истекшие акции в статус "Прошедшие"';

    public function handle()
    {
        $now = Carbon::now();
        
        $expiredPromotions = Promotion::active()
            ->where('end_date', '<', $now)
            ->get();

        if ($expiredPromotions->isEmpty()) {
            $this->info('Нет акций для обновления статуса.');
            return;
        }

        $count = 0;
        foreach ($expiredPromotions as $promotion) {
            $promotion->update(['status' => PromotionStatus::Expired]);
            $count++;
            $this->line("Акция '{$promotion->name}' переведена в статус 'Прошедшая'");
        }

        $this->info("Обновлено статусов: {$count}");
    }
}
