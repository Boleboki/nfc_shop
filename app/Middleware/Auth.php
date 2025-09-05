<?php

namespace App\Middleware;

use App\Core\JWT;

class Auth
{
    public function handle(): void
    {
        if (!isset($_COOKIE['token'])) {
            $this->redirectToLogin();
        }

        $jwt = new JWT();
        $payload = $jwt->decode($_COOKIE['token']);

        if (!$payload) {
            $this->redirectToLogin();
        }
    }

    private function redirectToLogin(): void
    {
        http_response_code(401);
        $url = url('/login');
        header("Location: {$url}");
        exit;
    }
}
