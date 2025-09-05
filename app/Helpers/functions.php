<?php

use App\Core\Response;

function dd($value)
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
}

function abort($code = 404)
{
    http_response_code($code);
    require base_path("app/views/{$code}.php");
    die();
}

function base_path($path)
{
    return BASE_PATH . $path;
}

function view($path, $attributes = [])
{
    try {
        $fullPath = base_path('app/views/' . $path);

        if (!file_exists($fullPath)) {
            throw new \Exception("View fajl ne postoji: $fullPath");
        }

        extract($attributes);
        include $fullPath;
    } catch (\Throwable $e) {
        http_response_code(500);
        echo "Greška pri učitavanju view fajla: " . htmlspecialchars($e->getMessage());
        throw $e;
        // Ako hoćeš: logovanje, fallback view itd.
    }
}


function redirect($path)
{
    $path = url($path);
    header("Location: {$path}");
    exit();
}


function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}
