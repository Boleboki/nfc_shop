<?php

namespace App\Middleware;

use App\Core\JWT;

class Master
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token'])) {
            deny("/");
        }

        $token = $_COOKIE['token'];
        $jwt = new JWT();
        $payload = $jwt->decode($token);

        if (!$payload || $payload['master'] !== 1)
            deny("/");
    }
}
