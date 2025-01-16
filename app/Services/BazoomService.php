<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BazoomService
{
    protected $baseUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = 'https://customerapi.bazoom.com/v1/marketplace';
        $this->username = env('BAZOOM_USERNAME');
        $this->password = env('BAZOOM_PASSWORD');
    }

    public function makeRequest(array $queryParams = [])
    {
        $response = Http::withBasicAuth($this->username, $this->password)
                        ->get($this->baseUrl, $queryParams);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => $response->body()
        ];
    }
}