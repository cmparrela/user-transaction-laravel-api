<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AuthorizationService
{
    private $url;

    public function __construct()
    {
        $this->url = env('AUTHORIZATION_SERVICE', 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    }

    public function isApproved($transactionId)
    {
        $response = Http::get($this->url, ['transaction' => $transactionId]);
        $body = $response->json();

        return !empty($body) && $body['message'] == 'Autorizado';
    }
}
