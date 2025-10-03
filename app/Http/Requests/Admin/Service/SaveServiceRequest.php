<?php

namespace App\Http\Requests\Admin\Service;

use App\Enums\Service\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'service.name' => 'required|string|max:255',
            'service.description' => 'nullable|string',
            'service.price' => 'required|numeric|min:0',
            'service.status' => ['required', Rule::enum(ServiceStatus::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'service.name' => 'название',
            'service.description' => 'описание',
            'service.price' => 'стоимость',
            'service.status' => 'статус',
        ];
    }
}
