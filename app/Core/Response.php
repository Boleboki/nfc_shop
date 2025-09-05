<?php
namespace App\Core;

class Response{
    const NOT_FOUND = 404;
    const FORBIDDEN = 403;

    public static function json($data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function text(string $message, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: text/plain');
        echo $message;
        exit;
    }
}