<?php

namespace App\Models;

use App\Core\Database;
use App\Core\JWT;
use App\Core\Logger;
use Firebase\JWT\Key;

class User extends Database
{
    private const USERS_TABLE = "users";
    public function __construct()
    {
        parent::__construct();
    }
    public function getAll()
    {
        $stmt = null;
        try {
            $this->ensureConnection();

            $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE);
            $stmt->execute();

            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_get_all", ["error" => $e->getMessage()]));
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }
    public function get_user_by_id($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE . " WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }

    public function get_user_by_username($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE . " WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }

    public function create($username, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO " . self::USERS_TABLE . " (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        return $stmt->execute();
    }

    public function admin(): bool
    {
        if (!isset($_COOKIE['token'])) return false;
        try {
            $token = $_COOKIE['token'];
            $payload = (new JWT)->decode($token);

            if (!$payload || $payload['admin'] !== 1) return false;

            return true;
        } catch (\Throwable $e) {
            throw new \Exception("Greška u tokenu: " . $e->getMessage(), 401);
        }
    }

    public function master(): bool
    {
        if (!isset($_COOKIE['token'])) return false;
        try {
            $token = $_COOKIE['token'];
            $payload = (new JWT)->decode($token);

            if (!$payload || !isset($payload['master']) || $payload['master'] !== 1) return false;

            return true;
        } catch (\Throwable $e) {
            throw new \Exception("Greška u tokenu: " . $e->getMessage(), 401);
        }
    }
}
