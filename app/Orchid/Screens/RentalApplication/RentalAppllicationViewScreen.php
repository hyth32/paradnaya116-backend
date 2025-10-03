<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Orchid\Screens\Base\BaseApplicationViewScreen;
use App\Orchid\Layouts\RentalApplication\RentalApplicationStatusBlockLayout;
use App\Orchid\Layouts\RentalApplication\RentalApplicationViewLayout;

class RentalAppllicationViewScreen extends BaseApplicationViewScreen
{
    protected string $editRouteName = 'rental-applications.edit';
    protected string $acceptMessage = 'Заявка на аренду принята';
    protected string $cancelMessage = 'Заявка на аренду отменена';

    public function name(): ?string
    {
        if (!$this->application) {
            return 'Заявка на аренду';
        }
        
        return "Заявка на аренду №{$this->application->id}";
    }

    protected function getViewLayoutClass(): string
    {
        return RentalApplicationViewLayout::class;
    }

    protected function getBackRoute(): string
    {
        return 'rental-applications.index';
    }

    public function layout(): iterable
    {
        return [
            RentalApplicationStatusBlockLayout::class,
            $this->getViewLayoutClass(),
        ];
    }
}
