<?php

namespace App\Controllers;

use App\Core\JWT;
use App\Core\Logger;
use App\Models\User;

class AuthController{
    private $jwt;
    public function __construct() {
        $this->jwt = new JWT;
    }

    public function login()
    {
        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        $username = $data['username'];
        $password = $data['password'];
        if(empty($username) || empty($password)) {
            echo json_encode([ 'success' => false, 'error' => "Polja ne smeju biti prazna"]);
            return;
        }
        $user = (new User)->get_user_by_username($username);
        
        if(!$user || !password_verify($password, $user['password'])){
            http_response_code(401);
            Logger::error("Pogresna lozinka ili username");
            echo json_encode(['success' => false, 'error' => "Pogresna lozinka ili username"]);
            return;
        }

        $token = $this->jwt->encode([
            'user_id' => $user['user_id'],
            'username' => $user['username'],
            'admin' => $user['is_admin'],
        ]);
        setcookie('token', $token, [
            'expires' => time() + 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => false,
            'samesite' => 'Lax'
        ]);
        Logger::info("Korisnik {$username} uspesno ulogovan");
        echo json_encode([ 'success' => true, 'redirect' => "/admin/dashboard"]);
    }

    public function logout()
    {
        $token = $_COOKIE['token'];
        setcookie('token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => false,
            'samesite' => 'Lax'
        ]);
        $data = $this->jwt->decode($token);
        Logger::info("Korisnik {$data['username']} uspesno izlogovan");
        echo json_encode(['message' => 'Uspesno izlogovan', 'redirect' => '/']);
    }
}