<?php

namespace App\Controllers;

use App\Core\Lang;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Product;
use Exception;
use Respect\Validation\Validator as v;

class AdminProductController
{
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
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
            $data = $this->data();
            $v = new Validator();

            $v->validate($data, [
                'name' => v::notEmpty()->addRule(v::alnum())->addRule(v::length(3, 20)),
                'price' => v::notEmpty()->addRule(v::numericVal()->addRule(v::min(0))->addRule(v::max(100000))),
                'stock_quantity' => $v->optionalIfFilled(v::intVal()->addRule(v::max(100000))),
                'description' => $v->optionalIfFilled(v::length(3, 150)->addRule($v->noSpecialChars())),
                'short_description' => $v->optionalIfFilled(v::length(3, 150)->addRule($v->noSpecialChars())),

            ]);
            if ($v->hasErrors()) {
                echo json_encode(['success' => false, 'errors' => $v->getErrors()]);
                return;
            }
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
            $data = $this->data();
            $v = new Validator();

            $v->validate($data, [
                'name' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20)),
                'price' => v::notEmpty()->addRule(v::numericVal()->addRule(v::min(0))->addRule(v::max(100000))),
                'stock_quantity' => $v->optionalIfFilled(v::intVal()->addRule(v::max(100000))),
                'description' => $v->optionalIfFilled(v::length(3, 150)->addRule($v->noSpecialChars())),
                'short_description' => $v->optionalIfFilled(v::length(3, 150)->addRule($v->noSpecialChars())),
            ]);
            if ($v->hasErrors()) {
                echo json_encode(['success' => false, 'errors' => $v->getErrors()]);
                return;
            }
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
