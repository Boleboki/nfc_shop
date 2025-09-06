<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;

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


    public function create($data)
    {
        try {
            $sql = "INSERT INTO products (name, description, short_description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssdis", $data['name'], $data['description'], $data['short_description'], $data['price'], $data['stock_quantity'], $data['image_url']);
            $stmt->execute();
        } catch (\Throwable $err) {
            Logger::error("Greška prilikom dodavanja proizvoda: " . $err->getMessage());
            http_response_code(500);
            throw $err;
        }
    }

    public function add_view($product_id)
    {
        $views = $this->get_views($product_id) + 1;
        $stmt = $this->conn->prepare("UPDATE products SET views = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $views, $product_id);
        $stmt->execute();
        return $views;
    }

    public function get_views($product_id)
    {
        $stmt = $this->conn->prepare("SELECT views FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["views"];
    }

    public function get_quantity($product_id)
    {
        $stmt = $this->conn->prepare("SELECT stock_quantity FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()["stock_quantity"];
    }

    public function read($product_id)
    {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function read_name($name)
    {
        $sql = "SELECT * FROM products WHERE url_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($product_id, $data)
    {
        $sql = "UPDATE products SET name = ?, description = ?, short_description = ?, price = ?, stock_quantity = ?, image_url = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssdisi", $data['name'], $data['description'], $data['short_description'], $data['price'], $data['stock_quantity'], $data['image_url'], $product_id);
        return $stmt->execute();
    }

    public function delete($product_id)
    {
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $product_id);
        return $stmt->execute();
    }

    public function decrease_quantity($product_id, $amount)
    {
        $quantity = $this->get_quantity($product_id) - $amount;
        $stmt = $this->conn->prepare("UPDATE products SET stock_quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        return $quantity;
    }

    public function most_viewed()
    {
        $sql = "SELECT * FROM products ORDER BY views DESC LIMIT 4";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function trackProductView($productId)
    {
        if (!$productId) return;

        $viewedProducts = $_COOKIE['viewed_products'] ?? '';
        $viewedArray = array_filter(explode(',', $viewedProducts));

        if (!in_array($productId, $viewedArray)) {
            // Povećaj broj pregleda
            $stmt = $this->conn->prepare("UPDATE products SET views = views + 1 WHERE product_id = ?");
            $stmt->bind_param("i", $productId);
            $stmt->execute();

            // Dodaj proizvod u cookie listu
            $viewedArray[] = $productId;
            $newCookieValue = implode(',', $viewedArray);
            setcookie('viewed_products', $newCookieValue, time() + 60 * 60, "/");
        }
    }
}
