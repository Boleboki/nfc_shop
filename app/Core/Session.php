<?php

namespace App\Core;

/**
 * Klasa Session služi za jednostavnu i centralizovanu kontrolu sesija u aplikaciji.
 * Omogućava pokretanje sesije, čuvanje, dohvatanje i brisanje podataka u sesiji,
 * kao i rad sa "flash" porukama koje traju samo do sledećeg učitavanja stranice.
 * 
 * @author
 * @version 1.0.1
 */
class Session
{
    /**
     * Pokreće PHP sesiju ako već nije pokrenuta.
     * Ne prima parametre i ne vraća ništa.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Proverava da li postoji vrednost za dati ključ u sesiji.
     * 
     * @param string $key Ključ koji se proverava
     * @return bool Vraća true ako ključ postoji i ima vrednost, false inače
     */
    public static function has($key)
    {
        // Koristi get() da proveri postojanje vrednosti i kastuje u bool
        return (bool) static::get($key);
    }

    /**
     * Postavlja vrednost u sesiju za dati ključ.
     * 
     * @param string $key Ključ pod kojim se čuva vrednost
     * @param mixed $value Vrednost koja se čuva
     */
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Dohvata vrednost iz sesije za dati ključ.
     * Ako vrednost ne postoji, vraća podrazumevanu vrednost.
     * 
     * @param string $key Ključ vrednosti
     * @param mixed $default Podrazumevana vrednost ako ključ ne postoji (default null)
     * @return mixed Vrednost iz sesije ili podrazumevana vrednost
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Postavlja "flash" poruku u sesiju.
     * Flash poruke traju samo do sledećeg učitavanja stranice.
     * 
     * @param string $key Ključ flash poruke
     * @param mixed $value Vrednost flash poruke
     */
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Briše sve flash poruke iz sesije.
     */
    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }

    /**
     * Dohvata flash poruku sa zadatim ključem.
     * Ako ne postoji, vraća podrazumevanu vrednost.
     * 
     * @param string $key Ključ flash poruke
     * @param mixed $default Podrazumevana vrednost (default prazan niz)
     * @return mixed Vrednost flash poruke ili podrazumevana vrednost
     */
    public static function get_flashed($key, $default = [])
    {
        return $_SESSION['_flash'][$key] ?? $default;
    }

    /**
     * Dohvata "stare" vrednosti unete u formu (koristi se za ponovni prikaz forme sa prethodnim podacima).
     * Ako ne postoji, vraća podrazumevanu vrednost.
     * 
     * @param string $key Ključ vrednosti
     * @param mixed $default Podrazumevana vrednost (default prazan string)
     * @return mixed Vrednost stare unete vrednosti ili podrazumevana vrednost
     */
    public static function old($key, $default = '')
    {
        return $_SESSION['_flash']['old'][$key] ?? $default;
    }

    /**
     * Briše sve podatke iz sesije.
     */
    public static function flush()
    {
        $_SESSION = [];
    }

    /**
     * Uništava sesiju u potpunosti:
     * - Briše sve podatke iz sesije
     * - Zatvara sesiju
     * - Briše sesijski kolačić u pregledaču
     */
    public static function destroy()
    {
        static::flush();
        session_destroy();

        // Briše kolačić sesije kako bi se potpuno uklonila sesija sa klijentske strane
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}
