<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SubmitSiteRequest extends FormRequest
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
            'url' => 'required|string|min:1|max:255|unique:sites,url',
            'name' => 'nullable|string|min:2|max:255',
            'description' => 'nullable|string|max:255',

            'obs' => 'nullable|string|max:600',

            'da' => 'nullable|integer',
            'dr' => 'nullable|integer',
            'traffic' => 'nullable|integer',
            'tf' => 'nullable|integer',

            'category_id' => 'nullable|integer|exists:categories,id',
            'language_id' => 'nullable|integer|exists:languages,id',
            'country_id' => 'nullable|integer|exists:countries,id',

            'link_type' => 'required|string|in:DOFOLLOW,NOFOLLOW',

            'gambling' => 'nullable|boolean',
            'cdb' => 'nullable|boolean',
            'cripto' => 'nullable|boolean',
            'sponsor' => 'nullable|boolean',
            'menu' => 'nullable|boolean',
            'banner' => 'nullable|boolean',

            'cost' => 'nullable|integer',
            'sale' => 'nullable|integer',
            'cost_coin' => 'nullable|in:BRL,EUR,USD',
            'sale_coin' => 'nullable|in:BRL,EUR,USD',

            'last_posted' => 'nullable|date',

            'seller_id' => 'nullable|integer|exists:sellers,id',
            'team_id' => 'nullable|integer|exists:teams,id',

            'owner_name' => 'nullable',
            'owner_email' => 'nullable',
            'owner_phone' => 'nullable',

            'bank' => 'nullable',
            'pix' => 'nullable',

            'phone' => 'nullable', 
            'paypal' => 'nullable',
            'instagram' => 'nullable',
            'facebook' => 'nullable',

            'types' => 'present|array|min:0',
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
            'url' => trim(Str::contains($this->url, '://') ?
                str_replace('www.', '', parse_url($this->url, PHP_URL_HOST)) :
                str_replace('www.', '', parse_url($this->url, PHP_URL_PATH))),
            'cost' => Helper::extractNumbersFromString($this->cost),
            'sale' => Helper::extractNumbersFromString($this->sale),
        ]);
    }
}