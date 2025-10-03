<?php

namespace App\Http\Requests\Admin\ServiceApplication;

use Illuminate\Foundation\Http\FormRequest;

class SaveServiceApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serviceApplication.customer_name' => 'required|string|max:255',
            'serviceApplication.customer_phone' => 'nullable|string|max:20',
            'serviceApplication.customer_email' => 'nullable|email|max:255',
            'serviceApplication.services' => 'required|array|min:1',
            'serviceApplication.services.*' => 'exists:services,id',
            'serviceApplication.deposit' => 'nullable|numeric|min:0',
            'serviceApplication.comment' => 'nullable|string',
            'serviceApplication.start_date' => 'nullable|date',
            'serviceApplication.end_date' => 'nullable|date|after_or_equal:serviceApplication.start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'serviceApplication.customer_name.required' => 'Имя клиента обязательно для заполнения',
            'serviceApplication.services.required' => 'Необходимо выбрать хотя бы одну услугу',
            'serviceApplication.services.min' => 'Необходимо выбрать хотя бы одну услугу',
            'serviceApplication.services.*.exists' => 'Выбранная услуга не существует',
            'serviceApplication.customer_email.email' => 'Некорректный формат email',
            'serviceApplication.end_date.after_or_equal' => 'Дата окончания должна быть больше или равна дате начала',
        ];
    }
}