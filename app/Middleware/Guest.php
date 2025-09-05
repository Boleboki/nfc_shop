<?php

namespace App\Middleware;

use App\Core\JWT;
use App\Core\Logger;

class Guest
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token'])) return;

        $jwt = new JWT();
        $payload = $jwt->decode($_COOKIE['token']);

        if ($payload) {
            $url = url('/');
            Logger::info("Redirecting authenticated user to dashboard: {$url}");
            header("Location: {$url}");
            exit;
        }
    }
}
