<?php

namespace App\Core;

/**
 * Klasa Logger je zadužena za logovanje poruka različitih nivoa u fajlove.
 * 
 * Logovi se čuvaju u direktorijumu storage/logs, u fajlovima po datumu.
 * Automatski briše stare log fajlove koji su stariji od definisanog broja dana.
 * 
 * Podržava različite nivoe logova: INFO, ERROR, WARNING, DEBUG.
 * 
 * @author
 * @version 1.0.1
 */
class Logger
{

    /**
     * Putanja do direktorijuma za log fajlove
     * @var string
     */
    protected static string $logDir = __DIR__ . '/../../storage/logs';

    // Dodaj statičku promenljivu za jezik log poruka
    protected static string $logLocale = LOG_LANGUAGE;

    // Metoda za podešavanje jezika logova
    public static function setLocale(string $locale): void
    {
        self::$logLocale = $locale;
    }

    /**
     * Snima poruku u log fajl sa pripadajućim nivoom (INFO, ERROR, ...)
     * 
     * @param string $level - nivo log poruke (npr. INFO, ERROR)
     * @param string $message - tekst poruke za logovanje
     * @return void
     * 
     * Proverava da li postoji direktorijum za logove, ako ne postoji kreira ga.
     * Pre svakog zapisa briše stare logove po definisanoj politici.
     * Log format je: [timestamp] [level] poruka
     */
    protected static function write(string $level, string $message): void
    {
        if (!file_exists(self::$logDir)) {
            mkdir(self::$logDir, 0777, true); // Kreira direktorijum ako ne postoji
        }

        // Briše stare log fajlove pre pisanja nove poruke
        self::deleteOldLogs();

        $timestamp = date("Y-m-d H:i:s"); // Trenutno vreme za timestamp loga
        $filename = self::$logDir . '/' . date("Y-m-d") . '.log'; // Log fajl po datumu
        $entry = "[$timestamp] [$level] $message" . PHP_EOL; // Format zapisa u log

        file_put_contents($filename, $entry, FILE_APPEND); // Dodaje poruku u log fajl
    }

    /**
     * Briše stare log fajlove koji su stariji od definisanog broja dana (LOG_RETENTION_DAYS)
     * 
     * @return void
     * 
     * Pregleda sve .log fajlove u direktorijumu i briše one čije je vreme izmene
     * starije od definisanog roka čuvanja.
     */
    protected static function deleteOldLogs(): void
    {
        $files = glob(self::$logDir . '/*.log'); // Dohvata sve log fajlove
        $now = time();

        foreach ($files as $file) {
            $filemtime = filemtime($file); // Vreme poslednje izmene fajla

            // Ako je fajl stariji od LOG_RETENTION_DAYS dana briše ga
            if ($filemtime !== false && ($now - $filemtime) > LOG_RETENTION_DAYS * 86400) {
                unlink($file);
            }
        }
    }
    /**
     * Prevodi poruku na trenutno podešeni jezik logova
     * 
     * @param string $message - ključ ili tekst poruke koja treba da se prevede
     * @param array $replace - niz vrednosti koje se ubacuju u mesta oznaka unutar poruke (opciono)
     * @return string - prevedena poruka spremna za logovanje
     * 
     * Koristi Lang::get metodu za prevođenje, bazirano na jeziku definisanom u $logLocale.
     */
    static function translate(string $message, array $replace = []): string
    {
        return Lang::get($message, $replace, self::$logLocale);
    }
    /**
     * Loguje informativnu poruku (INFO nivo)
     * 
     * @param string $message - tekst poruke
     * @return void
     */
    public static function info(string $message): void
    {
        self::write('INFO', $message);
    }

    /**
     * Loguje grešku (ERROR nivo)
     * 
     * @param string $message - tekst poruke
     * @return void
     */
    public static function error(string $message): void
    {
        self::write('ERROR', $message);
    }

    /**
     * Loguje upozorenje (WARNING nivo)
     * 
     * @param string $message - tekst poruke
     * @return void
     */
    public static function warning(string $message): void
    {
        self::write('WARNING', $message);
    }

    /**
     * Loguje debug poruku (DEBUG nivo)
     * 
     * @param string $message - tekst poruke
     * @return void
     */
    public static function debug(string $message): void
    {
        self::write('DEBUG', $message);
    }
}
