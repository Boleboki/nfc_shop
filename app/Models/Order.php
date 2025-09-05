<?php

namespace App\Models;

use App\Core\Database;

class Order extends Database
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($name, $surname, $phone_number, $email, $city, $postcode, $address, $products)
    {
        try {
            // 1. Priprema SQL upita za unos porudžbine
            $stmt = $this->conn->prepare("
            INSERT INTO orders (name, surname, phone_number, email, city, postcode, address)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

            if (!$stmt) {
                throw new \Exception("Greška prilikom pripreme INSERT upita za tabelu 'orders': " . $this->conn->error);
            }

            if (!$stmt->bind_param("sssssss", $name, $surname, $phone_number, $email, $city, $postcode, $address)) {
                throw new \Exception("Greška pri bindovanju parametara za 'orders': " . $stmt->error);
            }

            if (!$stmt->execute()) {
                throw new \Exception("Neuspešno izvršavanje upita za 'orders': " . $stmt->error);
            }

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

            return ["success" => true, "order_id" => $order_id];
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function get_orders($is_finished = -1)
    {
        $sql = "
            SELECT orders.order_id,
                   orders.name,
                   orders.surname,
                   orders.phone_number,
                   orders.email,
                   orders.city,
                   orders.postcode,
                   orders.address,
                   orders.created_at,
                   orders.is_finished,
                   order_items.quantity,
                   products.name AS product_name,
                   products.views,
                   products.stock_quantity,
                   products.image_url,
                   products.price
            FROM orders
            INNER JOIN order_items ON orders.order_id = order_items.order_id
            INNER JOIN products ON order_items.product_id = products.product_id
            WHERE (? = -1 OR orders.is_finished = ?)
            ORDER BY orders.created_at DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $is_finished, $is_finished);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $orders = [];

        foreach ($rows as $row) {
            $order_id = $row['order_id'];

            if (!isset($orders[$order_id])) {
                $orders[$order_id] = [
                    'order_id' => $row['order_id'],
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'phone_number' => $row['phone_number'],
                    'email' => $row['email'],
                    'city' => $row['city'],
                    'postcode' => $row['postcode'],
                    'address' => $row['address'],
                    'created_at' => $row['created_at'],
                    'is_finished' => $row['is_finished'],
                    'items' => []
                ];
            }

            $orders[$order_id]['items'][] = [
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'views' => $row['views'],
                'stock_quantity' => $row['stock_quantity'],
                'image_url' => $row['image_url'],
                'price' => $row['price']
            ];
        }

        return $orders;
    }

    public function set_finished($order_id, $finish = 1)
    {
        $stmt = $this->conn->prepare("UPDATE orders SET is_finished = ? WHERE order_id = ?");
        $stmt->bind_param("ii", $finish, $order_id);
        return $stmt->execute();
    }
}
