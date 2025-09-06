<?php

namespace App\Controllers;

use App\Core\Logger;
use App\Models\Product;
use App\Models\User;

class AdminUserController
{

    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }
    public function index()
    {
        try {
            return view("admin/users/index.view.php", ['users' => $this->user->getAll()]);
        } catch (\Throwable $e) {
            Logger::warning(Logger::translate("logs.users.error_show_index", ['error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }
}
