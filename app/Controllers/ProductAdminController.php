<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Models\Product;

class ProductAdminController{
    public function index(){
        return view("admin/products/index.view.php", [
            "products" => (new Product)->fetch_all()
        ]);
    }
    
    public function edit($id){
        $product = (new Product)->read($id);
        if(!$product){
            Logger::error("Korisnik je pokusao da menja nepostojeci proizvod");
            header("Location: /admin/products");
            exit;
        }
        return view("admin/products/edit.view.php", [
            "product" => $product
        ]);
    }
    public function delete($id){
        $product = new Product();
        $productName = $product->read($id)["name"] ?? '';
        if ($product->delete($id)) {
            Logger::info("Proizvod {$productName} uspesno obrisan");
            echo json_encode(["success" => true, 'message' => 'Proizvod je obrisan']);
        } else {
            Logger::error("Brisanje proizvoda {$productName} nije uspelo");
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => 'Brisanje nije uspelo']);
        }
    }
}