<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|in:WAITING,PRODUCTION,SUBMITTED,PROCESSING,PUBLISHED,INVOICE,COMPLETED',
            'site_id' => 'required|exists:sites,id',
            'client_id' => 'required|exists:clients,id',
            'seller_id' => 'required|exists:sellers,id',
            'url' => 'required|string|max:255',
            // 'ssl' => '',
            // 'broken' => '',
            'receipt_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',
            'payment_date' => 'nullable|date',
            'charged' => 'nullable',
            'paid' => 'nullable',
            'markup' => 'nullable',
            'comission' => 'nullable',
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
            'charged' => Helper::extractNumbersFromString($this->charged),
            'paid' => Helper::extractNumbersFromString($this->paid),
            'markup' => Helper::extractNumbersFromString($this->markup),
            'comission' => Helper::extractNumbersFromString($this->comission),
        ]);
    }
}
