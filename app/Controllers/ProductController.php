<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Models\Product;

class ProductController{
    public function index(){
        return view("/proizvodi/index.view.php", [
            'products' => (new Product)->fetch_all()
        ]);
    }

    public function show(string $name){
        $productObj = new Product();
        $product = $productObj->read_name($name);
        $productObj->trackProductView($product["product_id"]);
        return view("/proizvodi/show.view.php", [
            'product' => $product
        ]);
    }

}