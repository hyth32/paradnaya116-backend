<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationType;
use App\Orchid\Layouts\Base\BaseApplicationEditLayout;

class RentalApplicatonEditLayout extends BaseApplicationEditLayout
{
    protected function getCustomerNameTitle(): string
    {
        return 'Имя арендатора';
    }

    protected function getCustomerNamePlaceholder(): string
    {
        return 'Введите имя арендатора';
    }

    protected function getCustomerPhoneTitle(): string
    {
        return 'Телефон арендатора';
    }

    protected function getCustomerPhonePlaceholder(): string
    {
        return 'Введите телефон арендатора';
    }

    protected function getCustomerEmailTitle(): string
    {
        return 'Email арендатора';
    }

    protected function getCustomerEmailPlaceholder(): string
    {
        return 'Введите email арендатора';
    }

    protected function getStartDateTitle(): string
    {
        return 'Дата начала аренды';
    }

    protected function getStartDatePlaceholder(): string
    {
        return 'Выберите дату начала аренды';
    }

    protected function getEndDateTitle(): string
    {
        return 'Дата окончания аренды';
    }

    protected function getEndDatePlaceholder(): string
    {
        return 'Выберите дату окончания аренды';
    }

    protected function getTypeOptions(): array
    {
        return [
        ];
    }

    protected function shouldShowType(): bool
    {
        return false; // Не показываем поле типа для заявок на аренду
    }
}
