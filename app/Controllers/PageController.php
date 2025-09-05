<?php

namespace App\Controllers;

use App\Models\Product;

class PageController{
    public function home(){
        return view("index.view.php", [
            "products4" => (new Product)->most_viewed(),
            "products_all" => (new Product)->fetch_all()
        ]);
    }

    public function o_nama(){
        return view("o-nama.view.php");
    }
    public function kontakt(){
        
    }

    
}