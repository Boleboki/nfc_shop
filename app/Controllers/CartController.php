<?php

namespace App\Controllers;

use App\Core\Database;
use App\Models\Cart;

class CartController
{
    private $cart;
    public function __construct()
    {
        $this->cart = new Cart;
    }

    public function korpa()
    {
        return view("korpa.view.php", [
            'products' => $this->cart->getCartProducts()
        ]);
    }

    public function add()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $productId = intval($data['productId']);
        $quantity = intval($data['quantity']);
        $product = $this->cart->read($productId);
        if ($product && $product["quantity"] < 1) {
            echo json_encode(['success' => false, 'error' => 'Nevažeći podaci']);
            exit;
        }
        if (!$this->cart->add($productId, $quantity)) {
            echo json_encode(['success' => false, "error" => "Nije dodato u korpu"]);
            exit;
        }
        echo json_encode([
            'success' => true,
            "message" => "Uspešno dodato u korpu",
            "product" => $this->cart->read($productId),
            'totalCount' => $this->cart->countItems()
        ]);
    }

    public function remove(int $id)
    {
        if (!$this->cart->remove($id)) {
            echo json_encode(["success" => false, "error" => "Greska u brisanju proizvoda iz korpe"]);
            exit;
        }
        echo json_encode(["success" => true, "message" => "Uspesno uklonjen proizvod iz korpe"]);
    }
}
