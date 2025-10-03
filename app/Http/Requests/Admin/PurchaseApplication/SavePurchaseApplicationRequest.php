<?php

namespace App\Http\Requests\Admin\PurchaseApplication;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePurchaseApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'purchaseApplication.customer_name' => 'required|string|max:255',
            'purchaseApplication.customer_phone' => 'nullable|string|max:20',
            'purchaseApplication.customer_email' => 'nullable|email|max:255',
            'purchaseApplication.products' => 'required|array|min:1',
            'purchaseApplication.products.*' => [
                'required',
                'integer',
                Rule::exists('products', 'id')->where(function ($query) {
                    $query->where('status', 'active');
                }),
            ],
            'purchaseApplication.deposit' => 'nullable|numeric|min:0',
            'purchaseApplication.comment' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'purchaseApplication.customer_name.required' => 'Имя клиента обязательно для заполнения',
            'purchaseApplication.products.required' => 'Необходимо выбрать хотя бы один товар',
            'purchaseApplication.products.min' => 'Необходимо выбрать хотя бы один товар',
            'purchaseApplication.products.*.exists' => 'Выбранный товар не существует',
            'purchaseApplication.customer_email.email' => 'Некорректный формат email',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $products = $this->input('purchaseApplication.products', []);
            
            foreach ($products as $productId) {
                $product = Product::find($productId);
                if ($product && $product->getAvailableForPurchase() <= 0) {
                    $validator->errors()->add(
                        'purchaseApplication.products',
                        "Товар '{$product->name}' недоступен для покупки (нет в наличии)"
                    );
                }
            }
        });
    }
}