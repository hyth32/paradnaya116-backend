<?php

namespace App\Orchid\Screens\PurchaseApplication;

use App\Models\PurchaseApplication;
use App\Orchid\Layouts\PurchaseApplication\PurchaseApplicationViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PurchaseApplicationViewScreen extends Screen
{
    public ?PurchaseApplication $purchaseApplication = null;

    public function query(PurchaseApplication $purchaseApplication): iterable
    {
        $purchaseApplication->load(['products']);
        
        return [
            'purchaseApplication' => $purchaseApplication,
        ];
    }

    public function name(): ?string
    {
        return "Заявка на покупку №{$this->purchaseApplication->id}";
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('purchase-applications.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('purchase-applications.edit', $this->purchaseApplication->id),

            Button::make('Принять')
                ->canSee($this->purchaseApplication->isNew())
                ->icon('bs.check-circle')
                ->confirm('Вы уверены, что хотите принять заявку?')
                ->method('accept', [
                    'id' => $this->purchaseApplication->id,
                ]),

            Button::make('Отменить')
                ->canSee($this->purchaseApplication->isActive())
                ->icon('bs.x-circle')
                ->confirm('Вы уверены, что хотите отменить заявку?')
                ->method('cancel', [
                    'id' => $this->purchaseApplication->id,
                ]),

            Button::make('Завершить')
                ->canSee($this->purchaseApplication->isActive())
                ->icon('bs.check-circle-fill')
                ->confirm('Вы уверены, что хотите завершить заявку?')
                ->method('complete', [
                    'id' => $this->purchaseApplication->id,
                ]),
        ];
    }

    public function accept(int $id): void
    {
        $application = PurchaseApplication::findOrFail($id);
        $application->accept();
        Toast::success('Заявка принята');
    }

    public function cancel(int $id): void
    {
        $application = PurchaseApplication::findOrFail($id);
        $application->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete(int $id): void
    {
        $application = PurchaseApplication::findOrFail($id);
        $application->complete();
        Toast::success('Заявка завершена');
    }

    public function layout(): iterable
    {
        return [
            PurchaseApplicationViewLayout::class,
        ];
    }
}