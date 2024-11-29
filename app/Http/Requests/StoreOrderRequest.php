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
            'status' => ['required', 'in:WAITING,PRODUCTION,SUBMITTED,PROCESSING,PUBLISHED,INVOICE,COMPLETED'],
            'client_id' => ['required', 'exists:clients,id'],
            'type_id' => ['required', 'exists:types,id'],

            'items' => ['nullable', 'array', 'min:0'],

            'items.*.site_id' => ['required', 'exists:sites,id'],
            'items.*.seller_id' => ['nullable', 'exists:sellers,id'],
            
            'items.*.link' => ['nullable', 'string'],
            
            'items.*.cost' => ['nullable', 'integer'],
            'items.*.sale' => ['nullable', 'integer'],
            'items.*.comission' => ['nullable', 'integer'],

            'items.*.cost_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],
            'items.*.sale_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],
            'items.*.comission_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],

            'items.*.received' => ['nullable', 'boolean'],
            'items.*.paid' => ['nullable', 'boolean'],
            'items.*.comissioned' => ['nullable', 'boolean'],

            'items.*.link_status' => ['nullable', 'in:SUBMITTED,PRODUCTION,WAITING,PUBLISHED'],
            
            'receipt_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'payment_date' => ['nullable', 'date'],
        ];
    }
}
