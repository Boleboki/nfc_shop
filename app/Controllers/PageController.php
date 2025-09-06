<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Models\Product;

class PageController
{
    public function home()
    {
        try {
            return view("index.view.php", [
                "products4" => (new Product)->most_viewed(),
                "products_all" => (new Product)->fetch_all()
            ]);
        } catch (\Throwable $e) {
            Logger::error("Greška prilikom učitavanja početne strane: " . $e->getMessage());
            http_response_code(500);
            echo json_encode($e->getMessage());
        }
    }

    public function o_nama()
    {
        return view("o-nama.view.php");
    }
    public function kontakt() {}
}
