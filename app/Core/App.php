<?php

namespace App\Core;

/**
 * Klasa App je centralna tačka aplikacije koja upravlja pokretanjem
 * i inicijalizacijom potrebnih resursa, kao i rutiranjem HTTP zahteva.
 *
 * U konstruktoru se pokreće sesija i učitavaju osnovne konfiguracije,
 * pomoćne funkcije i definicija ruta.
 * Metoda run obrađuje ulazni URI i HTTP metodu te prosleđuje kontroleru.
 * 
 * @author
 * @version 1.0.1
 */
class App
{
    /**
     * Konstruktor klase App
     *
     * Pokreće sesiju i uključuje ključne fajlove potrebne za rad aplikacije:
     * konfiguraciju, pomoćne funkcije i definiciju ruta.
     *
     * Ne prima parametre i ne vraća vrednost.
     */
    public function __construct()
    {
        Session::start(); // Pokretanje PHP sesije

        // Učitavanje konfiguracionih i pomoćnih fajlova, kao i ruta
        require_once BASE_PATH . "config.php";
        require_once BASE_PATH . "app/Helpers/functions.php";
        require_once BASE_PATH . "routes.php";
    }

    /**
     * Pokreće aplikaciju - obrađuje HTTP zahtev i prosleđuje ga routeru.
     *
     * - Parsira URI iz server varijable i uklanja bazni put aplikacije
     * - Određuje HTTP metodu (po defaultu iz $_SERVER ili preko POST "_method")
     * - Poziva statičku metodu Router::route koja izvršava odgovarajuću rutu
     *
     * Ne prima parametre i ne vraća vrednost.
     */
    public function run(): void
    {
        Lang::setLocale($_COOKIE["lang"] ?? DEFAULT_LANGUAGE);
        // Parsiranje URL putanje iz kompletnog URI-ja
        $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        // Dobijanje baze putanje gde je aplikacija smeštena, npr. /ormarici/public
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);

        // Uklanjanje '/public' dela iz baze putanje da bi se dobio root aplikacije
        $basePath = str_replace('/public', '', $scriptName);

        // Ako URI počinje sa baznim putem, uklanja se taj deo
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Ako je URI prazan, postavlja ga na root '/'
        $uri = $uri ?: '/';

        // Određivanje HTTP metode, podržava overriding putem hidden "_method" polja u POST
        $method = $_POST["_method"] ?? $_SERVER["REQUEST_METHOD"];

        // Prosleđivanje URI-ja i metode Router klasi koja izvršava rutu
        Router::route($uri, $method);
    }
}
