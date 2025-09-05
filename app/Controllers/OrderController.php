<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Cart;
use App\Models\Order;

class OrderController
{
    private $order;
    public function __construct()
    {
        $this->order = new Order();
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$this->order->create($data["name"], $data["surname"], $data["phone"], $data["email"], $data["city"], $data["postcode"], $data["address"], $data["products"])) {
                echo json_encode(["success" => false, "error" => "Naručivanje nije uspelo"]);
                exit;
            }
            echo json_encode(["success" => true, "message" => "Uspešno naručivanje"]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }
}
