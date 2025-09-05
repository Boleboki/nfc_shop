<?php

declare(strict_types=1);

namespace App\Core;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Exception;

/**
 * Klasa JWT služi za enkodovanje i dekodovanje JSON Web Tokena (JWT)
 * koristeći biblioteku Firebase JWT.
 *
 * Omogućava kreiranje tokena sa korisničkim podacima i vremenskim ograničenjem,
 * kao i validaciju i izdvajanje podataka iz tokena.
 *
 * @author
 * @version 1.0.1
 */
class JWT
{
    /** 
     * Tajni ključ za potpisivanje i verifikaciju tokena (definisan u TOKEN konstanti)
     * @var string
     */
    private string $key;

    /**
     * Vreme isteka tokena u sekundama (default 3600s = 1 sat)
     * @var int
     */
    private int $expiration;

    /**
     * Konstruktor klase JWT
     * 
     * @param int $expiration - Trajanje validnosti tokena u sekundama
     * Ne vraća ništa.
     */
    public function __construct(int $expiration = 3600)
    {
        $this->key = TOKEN; // Učitavanje tajnog ključa iz konfiguracije
        $this->expiration = $expiration; // Postavljanje vremena isteka tokena
    }

    /**
     * Enkoduje podatke u JWT token.
     *
     * @param array $data - Podaci koje treba enkodovati unutar tokena
     * @return string - Vraća JWT token kao string
     *
     * Kreira payload sa vremenom izdavanja (iat), vremenom isteka (exp) i korisničkim podacima.
     * Koristi HS256 algoritam za potpisivanje tokena.
     */
    public function encode(array $data): string
    {
        $payload = [
            'iat' => time(), // Vreme kada je token kreiran
            'exp' => time() + $this->expiration, // Vreme kada token ističe
            'data' => $data, // Korisnički podaci koji se enkoduju u token
        ];

        return FirebaseJWT::encode($payload, $this->key, 'HS256');
    }

    /**
     * Dekoduje JWT token i vraća podatke iz tokena.
     *
     * @param string $jwt - JWT token kao string
     * @return array|null - Vraća dekriptovane podatke kao asocijativni niz ili null ako je token nevažeći
     *
     * U slučaju greške prilikom dekodiranja (npr. nevažeći token, istekao rok),
     * hvata Exception, beleži grešku u log i vraća null.
     */
    public function decode(string $jwt): ?array
    {
        try {
            // Dekodiranje tokena koristeći ključ i HS256 algoritam
            $decoded = FirebaseJWT::decode($jwt, new Key($this->key, 'HS256'));

            // Vraća samo deo podataka unutar tokena
            return (array) $decoded->data;
        } catch (Exception $e) {
            // Logovanje greške pri dekodiranju tokena
            Logger::error("Greska JWT klasa: " . $e->getMessage());
            return null;
        }
    }
}
