<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $to;
    private $message;

    public function __construct($to, $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    public function handle(NotificationService $notificationService)
    {
        $notificationService->send($this->to, $this->message);
        Log::info("Email sent to {$this->to} with the message {$this->message}");
    }
}
