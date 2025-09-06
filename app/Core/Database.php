<?php

/**
 *
 * Database.php
 * Osnovna klasa za rad sa bazom podataka koristeći mysqli.
 * Ova klasa pruža osnovne metode za konekciju, izvršavanje upita,
 * kao i preuzimanje rezultata (jedan ili više redova).
 * @author
 * @version 1.0.1
 */

namespace App\Core;

class Database
{
    protected $conn;  // MySQL konekcija
    protected $stmt;  // Pripremljena SQL izjava (statement)

    /**
     * Konstruktor klase
     * Automatski poziva konekciju sa bazom kada se instanca kreira
     */
    public function __construct()
    {
        $this->connect();
    }

    /**
     * Destruktor klase
     * Automatski zatvara konekciju sa bazom kada objekat više nije u upotrebi
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Usmerena metoda za uspostavljanje konekcije sa MySQL bazom
     * Postavlja `$this->conn` i proverava da li je uspešna
     * Baca Exception ako konekcija nije uspela
     */
    protected function connect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $this->conn = new \mysqli(DB_HOST, DB_USERNAME, DB_PASS, DB_NAME);
        $this->conn->set_charset("utf8mb4");

        // Provera uspešnosti konekcije
        if ($this->conn->connect_error) {
            Logger::error("Failed to connect to database: " . $this->conn->connect_error);
            throw new \Exception("Failed to connect to database: " . $this->conn->connect_error, 500);
        }
    }

    /**
     * Zatvara aktivnu konekciju sa bazom i oslobađa resurse
     */
    public function disconnect(): void
    {
        // Zatvara pripremljeni statement ako postoji
        if ($this->stmt) {
            $this->stmt->close();
            $this->stmt = null;
        }

        // Zatvara konekciju ako postoji
        if ($this->conn) {
            $this->conn->close();
            $this->conn = null;
        }
    }

    /**
     * Osigurava da je konekcija sa bazom aktivna
     * Ako nije, pokušava ponovo da se poveže
     */
    protected function ensureConnection(): void
    {
        if (!$this->conn || $this->conn->connect_error) {
            $this->connect();
        }
    }

    /**
     * Izvršava SQL upit uz opciono bindovanje parametara
     * @param string $query SQL upit (sa `?` za pripremljene parametre)
     * @param array $params Niz parametara za bindovanje (opciono)
     * @return $this Vraća istu instancu za dalje metode (`find`, `fetch_all`, itd.)
     * @throws \Exception ako priprema, bind ili izvršenje upita ne uspe
     */
    public function query($query, $params = [])
    {
        $this->stmt = $this->conn->prepare($query);
        if (!$this->stmt) {
            throw new \Exception("Failed to prepare statement: " . $this->conn->error);
        }

        // Ako su prosleđeni parametri, binduj ih kao stringove (s)
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Pretpostavka da su svi stringovi
            if (!$this->stmt->bind_param($types, ...$params)) {
                throw new \Exception("Failed to bind parameters: " . $this->stmt->error);
            }
        }

        if (!$this->stmt->execute()) {
            throw new \Exception("Failed to execute statement: " . $this->stmt->error);
        }

        return $this;
    }

    /**
     * Vraća jedan red rezultata kao asocijativni niz
     * @return array|null Asocijativni niz sa rezultatima ili null ako nema rezultata
     * @throws \Exception ako ne uspe dohvat rezultata
     */
    public function find()
    {
        $result = $this->stmt->get_result();
        if (!$result) {
            throw new \Exception("Failed to get result: " . $this->stmt->error);
        }
        return $result->fetch_assoc();
    }

    /**
     * Vraća jedan red rezultata kao asocijativni niz, ali baca Exception ako nema rezultata
     * @return array Asocijativni niz rezultata
     * @throws \Exception ako ne postoji nijedan red (404)
     */
    public function findOrFail()
    {
        $result = $this->find();
        if (!$result) {
            throw new \Exception("Record not found", 404);
        }
        return $result;
    }

    /**
     * Vraća sve redove iz rezultata kao niz asocijativnih nizova
     * @return array Niz rezultata
     * @throws \Exception ako ne uspe dohvat rezultata
     */
    public function fetch_all()
    {
        $result = $this->stmt->get_result();
        if (!$result) {
            throw new \Exception("Failed to get result: " . $this->stmt->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ručno zatvara pripremljeni SQL statement ako postoji
     */
    public function close()
    {
        if ($this->stmt) {
            $this->stmt->close();
        }
    }
}
