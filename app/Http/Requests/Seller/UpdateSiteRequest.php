<?php

namespace App\Http\Requests\Seller;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateSiteRequest extends FormRequest
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
            'url' => 'required|string|min:1|max:255',
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
            
            'gambling' => 'required|boolean',
            'cdb' => 'required|boolean',
            'cripto' => 'required|boolean',
            'sponsor' => 'required|boolean',
            'menu' => 'required|boolean',
            'banner' => 'required|boolean',

            'cost' => 'nullable|integer',
            'cost_coin' => 'nullable|in:BRL,EUR,USD',
            
            'last_posted' => 'nullable|date',

            'owner_name' => 'nullable',
            'owner_email' => 'nullable',
            'owner_phone' => 'nullable',

            'bank' => 'nullable',
            'pix' => 'nullable',

            'phone' => 'nullable',
            'paypal' => 'nullable',
            'instagram' => 'nullable',
            'facebook' => 'nullable',
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
            'url' => Str::contains($this->url, '://') ? 
                str_replace('www.', '', parse_url($this->url, PHP_URL_HOST)) :
                str_replace('www.', '', parse_url($this->url, PHP_URL_PATH)),

            'gambling' => !blank($this->gambling),
            'cdb' => !blank($this->cdb),
            'cripto' => !blank($this->cripto),
            'sponsor' => !blank($this->sponsor),
            'menu' => !blank($this->menu),
            'banner' => !blank($this->banner),

            'cost' => Helper::extractNumbersFromString($this->cost),
            'sale' => Helper::extractNumbersFromString($this->sale),
        ]);
    }
}
