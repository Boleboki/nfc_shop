<?php

namespace App\Controllers;

use App\Core\JWT;
use App\Core\Lang;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController
{
    private $jwt;
    public function __construct()
    {
        $this->jwt = new JWT;
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

    public function login()
    {
        try {
            $data = $this->data();

            $v = new Validator;
            $v->validate($data, [
                'username' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20)),
                'password' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20))
            ]);
            $username = $data['username'] ?? '';
            $password = $data['password'] ?? '';
            if ($v->hasErrors()) {
                Logger::warning(Logger::translate('logs.auth.validation_failed', ['username' => $username, 'errors' => json_encode($v->getErrors())]));
                echo json_encode([
                    'success' => false,
                    'errors' => $v->getErrors()
                ]);
                return;
            }

            $user = (new User)->getUserByUsername($username);

            if (!$user || !password_verify($password, $user['password'])) {
                Logger::error(Logger::translate('logs.auth.login_failed', ['username' => $username]));

                echo json_encode(['success' => false, 'error' => Lang::get("responses.auth.invalid_credentials")]);
                return;
            }

            $token = $this->jwt->encode([
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'admin' => $user['admin'],
                'master' => $user['master']
            ]);
            setcookie('token', $token, [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Lax'
            ]);
            Logger::info(Logger::translate('logs.auth.login_success', ['username' => $username]));

            echo json_encode(['success' => true, 'redirect' => url("/admin/dashboard")]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate('logs.auth.error_login', ["error" => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function logout()
    {
        try {
            $token = $_COOKIE['token'];
            setcookie('token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Lax'
            ]);
            $data = $this->jwt->decode($token);
            Logger::info(Logger::translate('logs.auth.logout_success', ['username' => $data['username']]));
            echo json_encode(['message' => Lang::get("responses.auth.logout_success"), 'redirect' => url('/')]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.auth.error_logout", ["error" => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
