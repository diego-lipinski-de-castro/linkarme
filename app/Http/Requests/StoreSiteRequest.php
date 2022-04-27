<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'url' => 'required|string|min:1|max:255',
            'name' => 'required|string|min:2|max:255',
            'description' => 'nullable|string|max:255',
            'obs' => 'nullable|string|max:600',
            'da' => 'nullable|integer',
            'dr' => 'nullable|integer',
            'traffic' => 'nullable|integer',
            'tf' => 'nullable|integer',
            'country' => 'nullable|string',
            'language' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'link_type' => 'required|string|in:DOFOLLOW,NOFOLLOW',
            
            'gambling' => 'nullable|boolean',
            'cdb' => 'nullable|boolean',
            'cripto' => 'nullable|boolean',
            'sponsor' => 'nullable|boolean',
            'ssl' => 'nullable|boolean',
            'broken' => 'nullable|boolean',

            'cost' => 'nullable',
            'sale' => 'nullable',
            'last_posted' => 'nullable|date',
        ];
    }
}
