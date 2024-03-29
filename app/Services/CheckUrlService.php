<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\SslCertificate\SslCertificate;

class CheckUrlService
{
    public function check(string $url)
    {
        if (blank($url)) {
            return null;
        }

        $code = null;
        $validSsl = null;

        // GuzzleHttp\Exception\ConnectException

        try {
            $code = (new \GuzzleHttp\Client())->request('GET', $url, [
                'connect_timeout' => 10,
                'timeout' => 30,
                'http_errors' => false,
                'allow_redirects' => false,
                'verify' => true,
                'debug' => true,
            ])->getStatusCode();
        } catch (Exception $e) {
            Log::channel('checkers')->debug($e);

            $code = null;
        }

        // Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate\HostDoesNotExist
        // Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate\UnknownError

        try {
            $validSsl = SslCertificate::createForHostName($url)->isValid();
        } catch (Exception $e) {
            Log::channel('checkers')->debug($e);

            $validSsl = false;
        }

        return (object) [
            'code' => $code,
            'ssl' => $validSsl,
        ];
    }
}
