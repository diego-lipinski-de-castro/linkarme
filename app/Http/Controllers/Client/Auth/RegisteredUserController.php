<?php

namespace App\Http\Controllers\Client\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Client/Auth/RegisterNew');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'locale' => ['nullable', 'in:en,es,pt'],
            'coin' => ['nullable', 'in:BRL,EUR,USD'],

            'notify_updates_via_email' => ['nullable', 'in:DAILY,WEEKLY,MONTHLY'],

            'phone' => ['nullable'],
        ]);

        $input['password'] = Hash::make($input['password']);

        $client = Client::create($input);

        event(new Registered($client));

        Auth::guard('client')->login($client);

        return redirect(RouteServiceProvider::CLIENT_HOME);
    }
}
