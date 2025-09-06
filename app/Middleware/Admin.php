<?php

namespace App\Middleware;

use App\Core\JWT;

class Admin
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token']))
            deny("/");

        $token = $_COOKIE['token'];
        $jwt = new JWT();
        $payload = $jwt->decode($token);

        if (!$payload || $payload['admin'] !== 1)
            deny("/");
    }
}
