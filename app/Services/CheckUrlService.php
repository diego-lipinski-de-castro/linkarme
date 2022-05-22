<?php

namespace App\Services;

use Spatie\SslCertificate\SslCertificate;

class CheckUrlService {

    public function check(string $url) {
        
        if(blank($url)) return null;

        $code = (new \GuzzleHttp\Client())->request('GET', $url, [
            'connect_timeout' => 10,
            'timeout' => 30,
            'http_errors' => false,
            'allow_redirects' => false,
            'verify' => true,
            'debug' => true,
        ])->getStatusCode();

        $validSsl = SslCertificate::createForHostName($url)->isValid();

        return (object) [
            'code' => $code,
            'ssl' => $validSsl,
        ];
    }
}