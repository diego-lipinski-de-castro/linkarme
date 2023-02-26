<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Jenssegers\Agent\Agent;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::query()
            ->orderBy('name')
            ->paginate();

        return Inertia::render('Clients/IndexNew', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Clients/CreateNew');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.edit', $client->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return Inertia::render('Clients/ShowNew', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client, Request $request)
    {
        return Inertia::render('Clients/EditNew', [
            'client' => $client,
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
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $input = $request->validateWithBag('updateProfileInformation', [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable'],
            'email' => ['required', 'email', 'max:255', Rule::unique('clients')->ignore($client->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],

            'locale' => ['nullable', 'in:en,es,pt'],
            'coin' => ['nullable', 'in:BRL,EUR,USD'],
            'full' => ['required', 'boolean'],
            'notify_updates_via_email' => ['nullable', 'in:DAILY,WEEKLY,MONTHLY'],
            
            'company_name' => ['nullable'],
            'website_url' => ['nullable'],
            'address_country' => ['nullable'],
            'address_state' => ['nullable'],
            'address_street_name' => ['nullable'],
            'address_street_number' => ['nullable'],
            'address_number' => ['nullable'],
            'address_zipcode' => ['nullable'],

            'phone' => ['nullable'],

            'invoice_company_name' => ['nullable'],
            'invoice_vat_number' => ['nullable'],
            'invoice_paypal' => ['nullable'],
            'invoice_address_country' => ['nullable'],
            'invoice_address_state' => ['nullable'],
            'invoice_address_street_name' => ['nullable'],
            'invoice_address_street_number' => ['nullable'],
            'invoice_address_number' => ['nullable'],
            'invoice_address_zipcode' => ['nullable'],

            'contact' => ['nullable'],
            'contact_where' => ['nullable'],
            'contact_id' => ['nullable'],
            'contact_how' => ['nullable'],
        ]);

        if (isset($input['photo'])) {
            $client->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $client->email &&
            $client instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($client, $input);
        } else {
            $client->forceFill(Arr::except($input, ['photo']))->save();
        }

        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return back();
    }
}
