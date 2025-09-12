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

        $query = RentalApplication::defaultSort('id', 'desc');

        match ($status) {
            RentalApplicationStatus::Active->value => $query->active(),
            RentalApplicationStatus::Canceled->value => $query->canceled(),
            RentalApplicationStatus::Completed->value => $query->completed(),
            default => $query->new(),
        };

        return [
            'rentalApplications' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Список заявок на аренду';
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

    public function remove(int $id): void
    {
        $rentalApplication = RentalApplication::findOrFail($id);   
        $rentalApplication->delete();
        Toast::success('Заявка на аренду удалена');
    }
}
