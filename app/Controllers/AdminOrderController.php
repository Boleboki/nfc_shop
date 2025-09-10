<?php

namespace App\Controllers;

use App\Core\Lang;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Respect\Validation\Validator as v;

class AdminOrderController
{
    private Order $order;

    public function __construct()
    {
        $this->order = new Order();
    }

    private function data(): ?array
    {
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            Logger::warning(Logger::translate("logs.general.invalid_data"));
            throw new \Exception(Lang::get("logs.general.invalid_data"));
        }

        return $data;
    }

    public function index()
    {
        try {
            return view("admin/orders/index.view.php", ["orders" => $this->order->getAll()]);
        } catch (\Throwable $err) {
            Logger::error($err->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $err->getMessage()]);
        }
    }

    public function getAll()
    {
        try {
            echo json_encode($this->order->getAll());
        } catch (\Throwable $err) {
            Logger::error($err->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "error" => $err->getMessage()]);
        }
    }

    public function updateStatus(int $orderId)
    {
        try {
            $data = $this->data(); // koristi privatnu metodu za čitanje JSON inputa

            if (!isset($data['status'])) {
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "error" => "order_id and status are required."
                ]);
                return;
            }
            $status = trim($data['status']);

            $this->order->updateStatus($orderId, $status);

            echo json_encode([
                "success" => true,
                "message" => "Order status updated successfully.",
                "status" => $status
            ]);
        } catch (\Throwable $e) {
            Logger::error("AdminOrderController updateStatus: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error" => $e->getMessage()
            ]);
        }
    }
}
