<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Logger;
use Exception;

class Order extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        try {
            // 1. Priprema SQL upita za unos porudžbine
            $stmt = $this->conn->prepare("
                INSERT INTO orders (name, surname, phone_number, email, city, postcode, address)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("sssssss", $data['name'], $data['surname'], $data['phone_number'], $data['email'], $data['city'], $data['postcode'], $data['address']);

            $stmt->execute();

            $order_id = $this->conn->insert_id;
            if (!$order_id) {
                throw new \Exception("Nije moguće dobiti ID unete porudžbine.");
            }

            $stmt = $this->conn->prepare("
                INSERT INTO order_items (order_id, product_id, quantity)
                VALUES (?, ?, ?)
            ");

            if (!$stmt) {
                throw new \Exception("Greška prilikom pripreme INSERT upita za 'order_items': " . $this->conn->error);
            }
            $products = $data['products'];
            foreach ($products as $product) {
                if (!isset($product['product_id'], $product['quantity'])) {
                    throw new \Exception("Proizvod nije validan: nedostaje 'product_id' ili 'quantity'.");
                }

                if (!$stmt->bind_param("iii", $order_id, $product["product_id"], $product["quantity"])) {
                    throw new \Exception("Greška pri bindovanju parametara za 'order_items': " . $stmt->error);
                }

                if (!$stmt->execute()) {
                    throw new \Exception("Neuspešno izvršavanje upita za 'order_items': " . $stmt->error);
                }
            }
            (new Cart)->destroy_cart();

            return true;
        } catch (\Throwable $e) {
            Logger::error($e->getMessage());
            throw $e;
        }
    }


    public function getAll(): array
    {
        try {
            $sql = "
            SELECT 
                o.order_id,
                o.name,
                o.surname,
                o.phone_number,
                o.email,
                o.city,
                o.postcode,
                o.address,
                o.created_at,
                o.status,
                oi.quantity,
                p.name AS product_name,
                p.views,
                p.stock_quantity,
                p.image_url,
                p.price
            FROM orders o
            INNER JOIN order_items oi ON o.order_id = oi.order_id
            INNER JOIN products p ON oi.product_id = p.product_id
        ";

            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                throw new \Exception("SQL prepare error: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                throw new \Exception("SQL execute error: " . $stmt->error);
            }

            $result = $stmt->get_result();
            $rows = $result->fetch_all(MYSQLI_ASSOC);

            $orders = [];

            foreach ($rows as $row) {
                $orderId = $row['order_id'];

                if (!isset($orders[$orderId])) {
                    $orders[$orderId] = [
                        'order_id'    => $row['order_id'],
                        'name'        => $row['name'],
                        'surname'     => $row['surname'],
                        'phone_number' => $row['phone_number'],
                        'email'       => $row['email'],
                        'city'        => $row['city'],
                        'postcode'    => $row['postcode'],
                        'address'     => $row['address'],
                        'created_at'  => $row['created_at'],
                        'status'      => $row['status'],
                        'items'       => []
                    ];
                }

                $orders[$orderId]['items'][] = [
                    'product_name'   => $row['product_name'],
                    'quantity'       => (int) $row['quantity'],
                    'views'          => (int) $row['views'],
                    'stock_quantity' => (int) $row['stock_quantity'],
                    'image_url'      => $row['image_url'],
                    'price'          => (float) $row['price']
                ];
            }

            return $orders;
        } catch (\Throwable $e) {
            Logger::error("getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        try {
            $allowedStatuses = ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'];

            if (!in_array($status, $allowedStatuses, true)) {
                throw new \Exception("Invalid status: " . htmlspecialchars($status));
            }

            $stmt = $this->conn->prepare("
                UPDATE orders
                SET status = ?
                WHERE order_id = ?
            ");

            if (!$stmt) {
                throw new \Exception("SQL prepare error: " . $this->conn->error);
            }

            if (!$stmt->bind_param("si", $status, $orderId)) {
                throw new \Exception("SQL bind_param error: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new \Exception("SQL execute error: " . $stmt->error);
            }

            return $stmt->affected_rows > 0;
        } catch (\Throwable $e) {
            Logger::error("updateStatus error: " . $e->getMessage());
            throw $e;
        }
    }
}
