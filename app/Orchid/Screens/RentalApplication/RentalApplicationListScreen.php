<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use App\Orchid\Layouts\RentalApplication\RentalApplicationListLayout;
use App\Orchid\Layouts\RentalApplication\RentalApplicationListTabLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RentalApplicationListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', RentalApplicationStatus::New->value);

        $query = RentalApplication::with(['products'])
            ->where('status', $status)
            ->defaultSort('id', 'desc');

        return [
            'rentalApplications' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Заявки на аренду';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('rental-applications.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            RentalApplicationListTabLayout::class,
            RentalApplicationListLayout::class,
        ];
    }

    public function accept(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->accept();
        Toast::success('Заявка принята');
    }

    public function cancel(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->complete();
        Toast::success('Заявка завершена');
    }

    public function remove(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->delete();
        Toast::success('Заявка на аренду удалена');
    }
}
