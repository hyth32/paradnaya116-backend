<?php

namespace App\Orchid\Screens\PurchaseApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\PurchaseApplication;
use App\Orchid\Layouts\PurchaseApplication\PurchaseApplicationListLayout;
use App\Orchid\Layouts\PurchaseApplication\PurchaseApplicationListTabMenu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PurchaseApplicationListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', RentalApplicationStatus::New->value);

        $query = PurchaseApplication::with(['products'])
            ->where('status', $status)
            ->defaultSort('id', 'desc');

        return [
            'purchaseApplications' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Заявки на покупку';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('purchase-applications.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PurchaseApplicationListTabMenu::class,
            PurchaseApplicationListLayout::class,
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
}