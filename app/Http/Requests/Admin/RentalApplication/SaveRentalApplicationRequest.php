<?php

namespace App\Http\Requests\Admin\RentalApplication;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SaveRentalApplicationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'rentalApplication.start_date' => Carbon::parse($this->input('rentalApplication.start_date'))->toDateTimeString(),
            'rentalApplication.end_date' => Carbon::parse($this->input('rentalApplication.end_date'))->toDateTimeString(),
        ]);
    }
    
    public function rules(): array
    {
        return [
            'rentalApplication.customer_name' => 'required|string',
            'rentalApplication.customer_phone' => 'nullable|phone:RU',
            'rentalApplication.customer_email' => 'nullable|email:rfc',
            'rentalApplication.products' => 'required|array|min:1',
            'rentalApplication.deposit' => 'nullable|decimal:0,2',
            'rentalApplication.comment' => 'nullable|string',
            'rentalApplication.start_date' => 'nullable|date',
            'rentalApplication.end_date' => 'nullable|date|after_or_equal:rentalApplication.startDate',
        ];
    }

    public function attributes(): array
    {
        return [
            'rentalApplication.customer_name' => 'Имя арендатора',
            'rentalApplication.customer_phone' => 'Телефон арендатора',
            'rentalApplication.customer_email' => 'Email аредатора',
            'rentalApplication.products' => 'Товары',
            'rentalApplication.deposit' => 'Сумма депозита',
            'rentalApplication.comment' => 'Комментарий к заявке',
            'rentalApplication.start_date' => 'Дата начала аренды',
            'rentalApplication.end_date' => 'Дата окончания аренды',
        ];
    }
}
