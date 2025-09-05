<?php

namespace App\Models;

use App\Core\Database;

class Cart extends Database
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getId(): string
    {
        if (isset($_COOKIE['cart_id']))
            return $_COOKIE['cart_id'];

        $cartId = bin2hex(random_bytes(16));
        setcookie('cart_id', $cartId, [
            'expires' => time() + 86400 * 7,
            'path' => '/',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        return $cartId;
    }

    public function getCartProducts()
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("
        SELECT p.product_id, p.name, p.price, c.quantity FROM products p 
        INNER JOIN cart c ON p.product_id = c.product_id WHERE c.cart_id = ?");
        $stmt->bind_param("s", $cartId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function read(int $productId)
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("
            SELECT c.quantity, 
            p.product_id, p.name, p.price, p.image_url, p.description
            FROM cart c
            INNER JOIN products p ON c.product_id = p.product_id
            WHERE c.cart_id = ? AND c.product_id = ?
        ");

        $stmt->bind_param("ii", $cartId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function add(int $productId, int $quantity = 1)
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("
        INSERT INTO cart (cart_id, product_id, quantity)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
        $stmt->bind_param("sii", $cartId, $productId, $quantity);
        $result = $stmt->execute();

        return $result;
    }

    public function remove($productId)
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("
        DELETE FROM cart WHERE cart_id = ? AND product_id = ?
        ");
        $stmt->bind_param("si", $cartId, $productId);
        $result = $stmt->execute();

        return $result;
    }
    public function get_cart_items()
    {
        return $_SESSION['cart'];
    }

    public function countItems()
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("
        SELECT * FROM cart WHERE cart_id = ?
        ");
        $stmt->bind_param("s", $cartId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $quantity = 0;
        foreach ($result as $cart) {
            $quantity += $cart["quantity"];
        }
        return $quantity;
    }

    public function get_cart_item($productId)
    {
        return $_SESSION['cart'][$productId];
    }

    public function destroy_cart()
    {
        $cartId = $this->getId();
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $stmt->bind_param("i", $cartId);
        return $stmt->execute();
    }
}
