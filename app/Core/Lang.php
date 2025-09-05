<?php

/**
 * Klasa Lang
 * --------------------------
 * Ova klasa služi za upravljanje jezičkim prevodima (multijezičnost) u aplikaciji.
 * Omogućava učitavanje prevoda iz fajlova, čuvanje trenutnog jezika, 
 * dinamičko biranje jezika, kao i zamenu vrednosti unutar prevodnih stringova.
 * 
 * @author 
 * @version 1.0.1
 */

namespace App\Core;

class Lang
{
    // Lista dostupnih jezika
    protected static $available = ['sr', 'en'];

    // Keširane prevodilačke vrednosti
    protected static array $translations = [];

    // Trenutno aktivni jezik (locale)
    protected static string $locale = DEFAULT_LANGUAGE;

    /**
     * Postavlja trenutni jezik aplikacije.
     * 
     * @param string $locale - Jezik koji želimo da postavimo (npr. 'en', 'sr')
     * 
     * @return void
     */
    public static function setLocale(string $locale): void
    {
        // Postavlja samo ako je jezik podržan, u suprotnom koristi podrazumevani jezik
        self::$locale = in_array($locale, self::$available) ? $locale : DEFAULT_LANGUAGE;
    }

    /**
     * Vraća trenutno postavljen jezik (locale).
     * 
     * @return string - Trenutno aktivni jezik
     */
    public static function getLocale(): string
    {
        // Vraća trenutni jezik samo ako je podržan, inače podrazumevani
        return in_array(self::$locale, self::$available) ? self::$locale : DEFAULT_LANGUAGE;
    }

    /**
     * Dohvata prevod na osnovu ključa i opciono menja placeholder-e.
     * 
     * @param string $key - Ključ prevoda u formatu 'datoteka.ključ1.ključ2'
     * @param array $replace - Asocijativni niz za zamenu u prevodu, npr. [ 'name' => 'Pera' ]
     * @param string|null $overrideLocale - Ako se prosledi, koristi se taj jezik umesto trenutnog
     * 
     * @return string - Vraća prevedeni string, ili ključ ako prevod ne postoji
     */
    public static function get(string $key, array $replace = [], ?string $overrideLocale = null): string
    {
        // Parsira ključ u segmente: 'auth.invalid' -> ['auth', 'invalid']
        $segments = explode('.', $key);

        // Ako nema dovoljno segmenata (npr. samo 'auth'), vraća originalni ključ
        if (count($segments) < 2) {
            return $key;
        }

        // Koristi prosleđeni jezik ako postoji, u suprotnom koristi trenutno postavljeni
        $locale = $overrideLocale ?? self::$locale;
        // Prvi segment označava naziv fajla
        $filePath = $segments[0];

        // Ako prevod za datu datoteku još nije učitan u keš
        if (!isset(self::$translations[$locale][$filePath])) {
            $path = base_path("lang/" . $locale . "/" . $filePath . ".json");
            // Ako fajl postoji, uključuje ga i čuva njegov sadržaj u kešu
            self::$translations[$locale][$filePath] = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }
        if (!isset(self::$translations[$locale][$filePath][$key])) {
            return $key;
        }
        $translation = self::$translations[$locale][$filePath][$key];

        // Zamenjuje placeholder-e u prevodu sa prosleđenim vrednostima
        foreach ($replace as $k => $v) {
            $translation = str_replace(":" . $k, $v, $translation);
        }

        return $translation;
    }

    /**
     * Vraća sve prevode iz jedne prevodne datoteke (grupe)
     * 
     * @param string $file - Naziv fajla sa prevodima (bez ekstenzije), npr. 'auth', 'messages'
     * @param string|null $overrideLocale - Ako se prosledi, koristi se taj jezik umesto trenutno postavljenog
     * 
     * @return array - Asocijativni niz sa svim prevodima iz zadate datoteke
     * 
     * Ako prevodna datoteka nije već učitana, pokušava da je učita sa diska i kešira njen sadržaj.
     */
    public static function getGroup(string $file, ?string $overrideLocale = null): array
    {
        // Koristi prosleđeni jezik ako postoji, u suprotnom koristi trenutno postavljeni
        $locale = $overrideLocale ?? self::$locale;

        // Ako fajl još nije učitan, pokušaj da ga učitaš
        if (!isset(self::$translations[$locale][$file])) {
            $path = base_path("lang/{$locale}/{$file}.json");
            // Ako fajl postoji, uključuje ga; u suprotnom prazno
            self::$translations[$locale][$file] = file_exists($path)
                ? json_decode(file_get_contents($path), true)
                : [];
        }

        return self::$translations[$locale][$file];
    }
}
