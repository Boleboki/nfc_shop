<?php

namespace App\Middleware;

class Middleware{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
        'admin' => Admin::class
    ];

    public static function resolve(array|string|null $middlewares): void
    {
        if (is_null($middlewares)) return;

        foreach ((array) $middlewares as $middleware) {
            if (is_string($middleware) && isset(self::MAP[$middleware])) {
                $middleware = self::MAP[$middleware];
            }

            if (!class_exists($middleware)) {
                throw new \Exception("Middleware class {$middleware} does not exist.");
            }

            (new $middleware)->handle();
        }
    }

}