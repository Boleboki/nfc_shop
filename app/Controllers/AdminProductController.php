<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Models\Product;
use Exception;

class AdminProductController
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }
    public function index()
    {
        return view("admin/products/index.view.php", [
            "products" => (new Product)->fetch_all()
        ]);
    }

    public function create()
    {
        return view("admin/products/create.view.php");
    }

    public function edit($id)
    {
        $product = $this->product->read($id);
        if (!$product) {
            Logger::error("Korisnik je pokusao da menja nepostojeci proizvod");
            redirect("/admin/products");
        }
        return view("admin/products/edit.view.php", [
            "product" => $product
        ]);
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->product->create($data);
            echo json_encode(['success' => true, 'message' => 'Uspešno dodat proizvod', 'redirect' => url("/admin/products")]);
        } catch (\Throwable $e) {
            Logger::error("Greška prilikom dodavanja proizvoda: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->product->update($id, $data);
            echo json_encode(['success' => true, 'message' => 'Uspešno izmenjen proizvod', 'redirect' => url("/admin/products")]);
        } catch (\Throwable $e) {
            Logger::error("Greška prilikom ažuriranja proizvoda: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            throw $e;
        }
    }


    public function delete($id)
    {
        $productName = $this->product->read($id)["name"] ?? '';
        if ($this->product->delete($id)) {
            Logger::info("Proizvod {$productName} uspesno obrisan");
            echo json_encode(["success" => true, 'message' => 'Proizvod je obrisan']);
        } else {
            Logger::error("Brisanje proizvoda {$productName} nije uspelo");
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => 'Brisanje nije uspelo']);
        }
    }
}
