<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateClientRequest extends FormRequest
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
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['nullable'],
            'full' => ['required', 'boolean'],
            'locale' => ['nullable', 'in:en,es,pt'],
            'coin' => ['nullable', 'in:BRL,EUR,USD,GBP'],
            'notify_updates_via_email' => ['nullable', 'in:DAILY,WEEKLY,MONTHLY'],
        ];
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
