<?php

namespace App\Http\Controllers\Client;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class ProfileController extends Controller
{
    use PasswordValidationRules;

    /**
     * Show the user profile screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        return Inertia::render('Client/Profile/ShowNew', [
            'sessions' => $this->sessions($request)->all(),
        ]);
    }

    /**
     * Get the current sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', $request->user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $input = $request->validateWithBag('updateProfileInformation', [
            'first_name' => [],
            'last_name' => [],
            'name' => ['required', 'string', 'max:255'],
            'birthday' => [],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
            
            'company_name' => [],
            'website_url' => [],
            'address_country' => [],
            'address_state' => [],
            'address_street_name' => [],
            'address_street_number' => [],
            'address_number' => [],
            'address_zipcode' => [],
            
            'phone' => [],

            'invoice_company_name' => [],
            'invoice_vat_number' => [],
            'invoice_paypal' => [],
            'invoice_address_country' => [],
            'invoice_address_state' => [],
            'invoice_address_street_name' => [],
            'invoice_address_street_number' => [],
            'invoice_address_number' => [],
            'invoice_address_zipcode' => [],
        ]);

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
            ])->save();
        }

        return back()->with('status', 'profile-information-updated');
    }

     /**
     * Delete the current user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPhoto()
    {
        auth()->user()->deleteProfilePhoto();

        return back(303)->with('status', 'profile-photo-deleted');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\PasswordUpdateResponse
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $input = $request->all();

        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        return back()->with('status', 'password-updated');
    }
}
