<?php

namespace App\Services;

use App\Exceptions\NotificationFailException;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    private $url;

    public function __construct()
    {
        $this->url = env('NOTIFICATION_SERVICE', 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');
    }

    public function send($to, $message)
    {
        $response = Http::get($this->url, ['to' => $to, 'message' => $message]);
        $body = $response->json();

        if (empty($body) || $body['message'] != 'Enviado') {
            throw new NotificationFailException();
        }
        return true;
    }
}
