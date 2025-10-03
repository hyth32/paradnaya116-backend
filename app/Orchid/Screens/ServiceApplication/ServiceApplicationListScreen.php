<?php

namespace App\Orchid\Screens\ServiceApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\ServiceApplication;
use App\Orchid\Layouts\ServiceApplication\ServiceApplicationListLayout;
use App\Orchid\Layouts\ServiceApplication\ServiceApplicationListTabMenu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceApplicationListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', RentalApplicationStatus::New->value);

        $query = ServiceApplication::with(['services'])
            ->where('status', $status)
            ->defaultSort('id', 'desc');

        return [
            'serviceApplications' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Заявки на услуги';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('service-applications.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ServiceApplicationListTabMenu::class,
            ServiceApplicationListLayout::class,
        ];
    }

    public function accept(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->accept();
        Toast::success('Заявка принята');
    }

    public function cancel(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->complete();
        Toast::success('Заявка завершена');
    }
}