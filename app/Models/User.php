<?php

namespace App\Models;

use App\Core\Database;
use App\Core\JWT;
use App\Core\Logger;
use finfo;
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
    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE . " WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE . " WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . self::USERS_TABLE . " WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }

    public function create($data)
    {
        $stmt = null;
        try {
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO " . self::USERS_TABLE . " (username, email, password, admin, active, master) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssiii", $data['username'], $data['email'], $hashed_password, $data['admin'], $data['active'], $data['master']);
            return $stmt->execute();
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_create", ['error' => $e->getMessage()]));
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function update(int $id, array $data): bool
    {
        $stmt = null;
        try {
            $this->ensureConnection();
            $this->conn->begin_transaction();

            $stmt = $this->conn->prepare("UPDATE " . self::USERS_TABLE . " SET username = ?, email = ?, admin = ?, master = ?, active = ? WHERE user_id = ?");
            $stmt->bind_param("ssiiii", $data['username'], $data['email'], $data['admin'], $data['master'], $data['active'], $id);

            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            Logger::error(Logger::translate('users.error_edit', ['id' => $id, 'error' => $e->getMessage()]));
            throw $e; // Prosleđuje izuzetak dalje
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function updatePassword($userId, $password)
    {
        $stmt = null;
        try {
            $this->ensureConnection();
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE " . self::USERS_TABLE . " SET password = ? WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $password_hashed, $userId);
            return $stmt->execute();
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_update", ['id' => $userId, 'error' => $e->getMessage()]));
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function delete($userId)
    {
        $stmt = null;
        try {
            $this->ensureConnection();
            $sql = "DELETE FROM " . self::USERS_TABLE . " WHERE user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            return $stmt->execute();
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_delete", ['id' => $userId, "error" => $e->getMessage()]));
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
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
            throw $e;
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
            throw $e;
        }
    }

    public function currentUser(): array|false
    {
        if (!isset($_COOKIE['token'])) {
            return false;
        }

        try {
            $token = $_COOKIE['token'];
            $payload = (new JWT)->decode($token);

            if (!$payload || !isset($payload['user_id'])) {
                return false;
            }

            return $this->getUserById((int)$payload['user_id']);
        } catch (\Throwable $e) {
            Logger::error("Greška pri dohvaćanju trenutnog korisnika: " . $e->getMessage());
            throw $e;
        }
    }
}
