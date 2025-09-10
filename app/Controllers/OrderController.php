<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Logger;
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
            $this->order->create($data);
            echo json_encode(["success" => true, "message" => "Uspešno naručivanje"]);
        } catch (\Throwable $e) {
            Logger::error($e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
    }
}
