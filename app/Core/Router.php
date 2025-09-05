<?php

namespace App\Core;

use App\Middleware\Middleware;

/**
 * Klasa Router je zadužena za upravljanje rutama aplikacije.
 * Omogućava definisanje ruta za različite HTTP metode (GET, POST, DELETE, PUT, PATCH)
 * i povezivanje ruta sa kontrolerima i njihovim metodama.
 * 
 * Takođe omogućava primenu middleware-a na određene rute.
 * 
 * Klasa sadrži logiku za parsiranje URL-ova, pronalaženje odgovarajuće rute,
 * pozivanje pripadajućeg kontrolera sa parametrima i prikazivanje greške 404 ako ruta ne postoji.
 * 
 * @author
 * @version 1.0.1
 */
class Router
{
    /**
     * Niz definisanih ruta
     * Svaka ruta je niz sa ključevima: uri, controller, method, middleware
     * @var array
     */
    protected static array $routes = [];

    /**
     * Dodaje novu rutu u listu ruta.
     * 
     * @param string $method HTTP metoda (GET, POST, itd.)
     * @param string $uriPattern URI obrazac (može sadržavati parametre u vitičastim zagradama)
     * @param string $controller Naziv kontrolera i metode u formatu Controller@method ili samo ime fajla
     * @return static Vraća instancu Routera radi lančanog poziva
     */
    protected static function add(string $method, string $uriPattern, string $controller): static
    {
        static::$routes[] = [
            'uri' => $uriPattern,
            'controller' => $controller,
            'method' => strtoupper($method), // HTTP metoda velika slova radi lakšeg poređenja
            'middleware' => null // Middleware po default-u nema
        ];
        return new static();
    }

    /**
     * Definiše GET rutu.
     * 
     * @param string $uri URI ruta
     * @param string $controller Kontroler ili metoda koja se poziva
     * @return static
     */
    public static function get(string $uri, string $controller): static
    {
        return static::add("GET", $uri, $controller);
    }

    /**
     * Definiše POST rutu.
     * 
     * @param string $uri URI ruta
     * @param string $controller Kontroler ili metoda koja se poziva
     * @return static
     */
    public static function post(string $uri, string $controller): static
    {
        return static::add("POST", $uri, $controller);
    }

    /**
     * Definiše DELETE rutu.
     * 
     * @param string $uri URI ruta
     * @param string $controller Kontroler ili metoda koja se poziva
     * @return static
     */
    public static function delete(string $uri, string $controller): static
    {
        return static::add("DELETE", $uri, $controller);
    }

    /**
     * Definiše PATCH rutu.
     * 
     * @param string $uri URI ruta
     * @param string $controller Kontroler ili metoda koja se poziva
     * @return static
     */
    public static function patch(string $uri, string $controller): static
    {
        return static::add("PATCH", $uri, $controller);
    }

    /**
     * Definiše PUT rutu.
     * 
     * @param string $uri URI ruta
     * @param string $controller Kontroler ili metoda koja se poziva
     * @return static
     */
    public static function put(string $uri, string $controller): static
    {
        return static::add("PUT", $uri, $controller);
    }

    /**
     * Dodaje middleware za poslednju definisanu rutu.
     * Middleware se može proslediti kao string ili niz stringova.
     * 
     * @param array|string $middleware Middleware ili niz middleware imena
     * @return static
     */
    public function only(array|string $middleware): static
    {
        // Postavlja middleware za poslednju dodatu rutu (koristi array_key_last za indeks)
        static::$routes[array_key_last(static::$routes)]['middleware'] = (array) $middleware;
        return $this;
    }

    /**
     * Pokreće rutiranje na osnovu URI-ja i HTTP metode.
     * 
     * Pretražuje definisane rute i ako pronađe podudaranje, izvršava odgovarajući kontroler.
     * Poziva Middleware pre kontrolera.
     * Ako ruta nije pronađena, prikazuje stranicu sa greškom 404.
     * 
     * @param string $uri URI sa kojim se poredi ruta
     * @param string $method HTTP metoda (GET, POST, ...)
     * @return void
     */
    public static function route(string $uri, string $method): void
    {
        foreach (static::$routes as $route) {
            // Pravi regex obrazac iz URI obrasca, zamenjujući parametre {param} sa regex grupom
            $pattern = "@^" . preg_replace("/\{[a-zA-Z_]+\}/", "([a-zA-Z0-9_-]+)", $route['uri']) . "$@";

            // Provera da li URI i metoda odgovaraju trenutnoj ruti
            if (preg_match($pattern, $uri, $matches) && strtoupper($method) === $route['method']) {
                array_shift($matches); // Uklanja ceo poklapanje sa početka niza

                // Pokreće middleware ako je definisan
                Middleware::resolve($route['middleware']);

                // Poziva kontroler sa prosleđenim parametrima iz URI-ja
                static::invokeController($route['controller'], $matches);
                return; // Ruta je obrađena, izlazak iz funkcije
            }
        }

        // Ako nijedna ruta nije odgovarala, prikazuje 404 stranicu
        static::abort();
    }

    /**
     * Poziva kontroler na osnovu stringa definisanog u ruti.
     * 
     * String može biti:
     * - samo ime fajla kontrolera (bez '@'), u tom slučaju se fajl samo učitava
     * - ili u formatu "Controller@method", tada se kreira objekat i poziva metoda
     * 
     * @param string $controllerString Naziv kontrolera ili fajla
     * @param array $params Parametri koje treba proslediti metodi kontrolera
     * @return mixed Vraća rezultat poziva kontrolera/metode
     * @throws \Exception Ako fajl, klasa ili metoda ne postoje
     */
    protected static function invokeController(string $controllerString, array $params = []): mixed
    {
        // Ako string ne sadrži '@', radi se o običnom fajlu koji se samo require-uje
        if (!str_contains($controllerString, '@')) {
            $path = base_path("app/Controllers/" . $controllerString);
            if (!file_exists($path)) {
                throw new \Exception("Controller file $path not found");
            }

            return require $path;
        }

        // Parsira string u naziv klase i metodu
        [$controllerName, $method] = explode('@', $controllerString);
        $controllerClass = "App\\Controllers\\$controllerName";

        // Proverava da li klasa postoji
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller $controllerClass not found");
        }

        $controller = new $controllerClass();

        // Proverava da li metoda postoji u klasi
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method $method not found in $controllerClass");
        }

        // Poziva metodu kontrolera sa parametrima (npr. ID iz URL-a)
        return call_user_func_array([$controller, $method], $params);
    }

    /**
     * Vraća URL prethodne strane sa koje je korisnik došao.
     * Ako nije dostupan, vraća root '/'
     * 
     * @return string
     */
    public static function previousUrl(): string
    {
        return $_SERVER['HTTP_REFERER'] ?? '/';
    }

    /**
     * Prikazuje stranicu sa greškom i prekida dalje izvršavanje.
     * 
     * @param int $code HTTP status kod (default 404)
     * @return void
     */
    protected static function abort(int $code = 404): void
    {
        http_response_code($code);
        require base_path("app/views/errors/{$code}.php");
        die();
    }
}
