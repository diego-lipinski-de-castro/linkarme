<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateSellerRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['nullable'],
            'comission' => ['nullable', 'integer'],
            'comission_coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],
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
            'comission' => Helper::extractNumbersFromString($this->comission),
        ]);
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $input = data_get($this->validator->validated(), $key, $default);

        if (blank($this->password)) {
            unset($input['password']);

            return $input;
        }

        return array_merge($input, [
            'password' => Hash::make($this->password),
        ]);
    }
}
