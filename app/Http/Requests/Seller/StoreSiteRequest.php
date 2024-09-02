<?php

namespace App\Http\Requests\Seller;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreSiteRequest extends FormRequest
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

            'rules' => 'nullable',
            'obs' => 'nullable|string|max:600',

            'da' => 'nullable|integer|min:0|max:100',
            'dr' => 'nullable|integer|min:0|max:100',
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
            'promo' => 'required|boolean',
            'menu' => 'nullable|boolean',
            'banner' => 'nullable|boolean',
            'google_news' => 'required|boolean',

            'links' => 'nullable|integer',
            'embed' => 'required|boolean',
            'images' => 'nullable|integer',

            'cost' => 'nullable|integer',
            'cost_coin' => 'nullable|in:BRL,EUR,USD,GBP',

            'last_posted' => 'nullable|date',

            'team_id' => 'nullable|integer|exists:teams,id',

            'owner_name' => 'nullable',
            'owner_role' => 'nullable',
            'owner_email' => 'nullable',
            'owner_phone' => 'nullable',

            'bank' => 'nullable',
            'pix' => 'nullable',
            'global_account' => 'nullable',
            'paypal' => 'nullable',
            'phone' => 'nullable',
            'instagram' => 'nullable',
            'facebook' => 'nullable',

            'restrict' => 'required|boolean',
            'restrict_detail' => 'nullable',

            'archive_article' => 'required|boolean',
            'archive_due' => 'nullable',

            'example_article' => 'nullable',

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
            // 'sale' => Helper::extractNumbersFromString($this->sale),
        ]);
    }
}
