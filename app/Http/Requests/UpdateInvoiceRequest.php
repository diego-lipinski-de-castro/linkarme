<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => ['nullable', 'integer'],
            'value_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],

            'discount' => ['nullable', 'integer'],
            'discount_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],

            'paid' => ['nullable', 'boolean'],

            'bank' => ['nullable', 'in:MERCURY,PAYPAL,WISE'],
            'payment_link' => ['nullable', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'value' => Helper::extractNumbersFromString($this->value),
            'discount' => Helper::extractNumbersFromString($this->discount),
        ]);
    }
}
