<?php

namespace App\Http\Requests\Admin\Promotion;

use App\Enums\Promotion\PromotionStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SavePromotionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'promotion.start_date' => Carbon::parse($this->input('promotion.start_date'))->toDateTimeString(),
            'promotion.end_date' => Carbon::parse($this->input('promotion.end_date'))->toDateTimeString(),
        ]);
    }

    public function rules(): array
    {
        return [
            'promotion.name' => 'required|string|max:255',
            'promotion.image' => 'nullable|string',
            'promotion.description' => 'nullable|string',
            'promotion.start_date' => 'required|date',
            'promotion.end_date' => 'required|date|after:promotion.start_date',
            'promotion.status' => 'required|in:' . implode(',', array_column(PromotionStatus::cases(), 'value')),
        ];
    }

    public function attributes(): array
    {
        return [
            'promotion.name' => 'Название акции',
            'promotion.image' => 'Изображение',
            'promotion.description' => 'Описание',
            'promotion.start_date' => 'Дата начала',
            'promotion.end_date' => 'Дата окончания',
            'promotion.status' => 'Статус',
        ];
    }

    public function messages(): array
    {
        return [
            'promotion.name.required' => 'Название акции обязательно для заполнения',
            'promotion.start_date.required' => 'Дата начала обязательна для заполнения',
            'promotion.end_date.required' => 'Дата окончания обязательна для заполнения',
            'promotion.end_date.after' => 'Дата окончания должна быть позже даты начала',
            'promotion.status.required' => 'Статус обязателен для заполнения',
        ];
    }
}