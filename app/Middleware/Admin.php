<?php

namespace App\Middleware;

use App\Core\JWT;

class Admin
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token'])) {
            $this->deny("/");
        }

        $token = $_COOKIE['token'];
        $jwt = new JWT();
        $payload = $jwt->decode($token);

        if (!$payload || empty($payload['admin']))
            $this->deny("/test");
    }

    private function deny(string $redirectTo): void
    {
        http_response_code(403);
        $redirectTo = url($redirectTo);
        header("Location: {$redirectTo}");
        exit;
    }
}
