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
            'seller_id' => 'nullable|integer|exists:sellers,id',

            'url' => 'required|string|min:1|max:255|unique:sites,url',

            'owner_name' => 'nullable',
            'owner_owner' => 'nullable',
            'owner_email' => 'nullable',
            'owner_phone' => 'nullable',

            'promo' => 'required|boolean',

            'restrict' => 'required|boolean',
            'restrict_detail' => 'nullable',

            'archive_article' => 'required|boolean',
            'archive_due' => 'nullable',

            'links' => 'nullable|integer',
            'embed' => 'required|boolean',
            'images' => 'nullable|integer',
            'sponsor' => 'required|boolean',

            'example_article' => 'nullable',

            'rules' => 'nullable',
            'obs' => 'nullable',

            'google_news' => 'required|boolean',

            'cost' => 'nullable|integer',
            'cost_coin' => 'nullable|in:BRL,EUR,USD',

            'paypal' => 'nullable',
            'pix' => 'nullable',
            'global_account' => 'nullable',

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
        ]);
    }
}
