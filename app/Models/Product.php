<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use Exception;

class Product extends Database
{

    const PRODUCTS_TABLE = "products";

    public function __construct()
    {
        parent::__construct();
    }

    public function fetch_all()
    {
        $sql = "SELECT * FROM " . self::PRODUCTS_TABLE;
        $result = $this->conn->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAll()
    {
        $sql = "SELECT name, url_name, description, short_description, price, image_url FROM " . self::PRODUCTS_TABLE;
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByUrlName(string $urlName)
    {
        $sql = "SELECT * FROM " . self::PRODUCTS_TABLE . " WHERE url_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $urlName);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?? false;
    }


    public function create($data)
    {
        $stmt = null;
        try {
            $this->ensureConnection();
            $this->conn->begin_transaction();
            $sql = "INSERT INTO " . self::PRODUCTS_TABLE . " (name, description, short_description, price, stock_quantity, image_url, url_name) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssdiss", $data['name'], $data['description'], $data['short_description'], $data['price'], $data['stock_quantity'], $data['image_url'], $data['url_name']);
            $stmt->execute();
            $this->conn->commit();
        } catch (\Throwable $e) {
            $this->conn->rollback();
            Logger::error("Greška prilikom dodavanja proizvoda: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function add_view($product_id)
    {
        $stmt = null;
        try {
            $this->ensureConnection();
            $views = $this->get_views($product_id) + 1;
            $stmt = $this->conn->prepare("UPDATE " . self::PRODUCTS_TABLE . " SET views = ? WHERE product_id = ?");
            $stmt->bind_param("ii", $views, $product_id);
            $stmt->execute();
            return $views;
        } catch (\Throwable $e) {
            Logger::error("Greška dodavanje pregleda proizvodu: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->close();
            }
        }
    }

    public function get_views($product_id)
    {
        $stmt = $this->conn->prepare("SELECT views FROM " . self::PRODUCTS_TABLE . " WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["views"];
    }

    public function get_quantity($product_id)
    {
        $stmt = $this->conn->prepare("SELECT stock_quantity FROM " . self::PRODUCTS_TABLE . " WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["stock_quantity"];
    }

    public function read($product_id)
    {
        $sql = "SELECT * FROM " . self::PRODUCTS_TABLE . " WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function read_name($name)
    {
        $sql = "SELECT * FROM " . self::PRODUCTS_TABLE . " WHERE url_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($product_id, $data)
    {
        $sql = "UPDATE " . self::PRODUCTS_TABLE . " SET name = ?, description = ?, short_description = ?, price = ?, stock_quantity = ?, image_url = ?, url_name = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssdissi", $data['name'], $data['description'], $data['short_description'], $data['price'], $data['stock_quantity'], $data['image_url'], $data['url_name'], $product_id);
        return $stmt->execute();
    }

    public function delete($product_id)
    {
        $sql = "DELETE FROM " . self::PRODUCTS_TABLE . " WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    public function decrease_quantity($product_id, $amount)
    {
        $quantity = $this->get_quantity($product_id) - $amount;
        $stmt = $this->conn->prepare("UPDATE " . self::PRODUCTS_TABLE . " SET stock_quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        return $quantity;
    }

    public function most_viewed()
    {
        try {
            $this->ensureConnection();
            $sql = "SELECT * FROM " . self::PRODUCTS_TABLE . " ORDER BY views DESC LIMIT 4";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (\Throwable $e) {
            Logger::error("Greška prilikom vraćanja proizvoda sa najviše pregleda: " . $e->getMessage());
            throw $e;
        }
    }

    public function trackProductView($productId)
    {
        if (!$productId) return;

        $viewedProducts = $_COOKIE['viewed_products'] ?? '';
        $viewedArray = array_filter(explode(',', $viewedProducts));

        if (!in_array($productId, $viewedArray)) {
            // Povećaj broj pregleda
            $stmt = $this->conn->prepare("UPDATE " . self::PRODUCTS_TABLE . " SET views = views + 1 WHERE product_id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            // Dodaj proizvod u cookie listu
            $viewedArray[] = $productId;
            $newCookieValue = implode(',', $viewedArray);
            setcookie('viewed_products', $newCookieValue, time() + 60 * 60, "/");
        }
    }
}
