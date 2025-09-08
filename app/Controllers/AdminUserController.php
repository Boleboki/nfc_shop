<?php

namespace App\Controllers;

use App\Core\Lang;
use App\Core\Logger;
use App\Core\Validator;
use App\Models\Product;
use App\Models\User;
use Respect\Validation\Validator as v;

class AdminUserController
{

    private User $user;

    public function __construct()
    {
        $this->user = new User();
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
        try {
            return view("admin/users/index.view.php", ['users' => $this->user->getAll()]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_show_index", ['error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function create()
    {
        try {
            return view("admin/users/create.view.php");
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_show_create", ['error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function store()
    {
        try {
            $data = $this->data();
            $username = $data['username'] ?? '';

            $v = new Validator();
            $v->validate($data, [
                'username' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20)),
                'password' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20)),
                'email' => v::notEmpty()->addRule(v::email()),
            ]);
            if ($v->hasErrors()) {
                Logger::warning(Logger::translate(
                    "logs.users.validation_failed",
                    ['username' => $username, 'errors' => json_encode($v->getErrors())]
                ));
                echo json_encode(["success" => false, 'errors' => $v->getErrors()]);
                return;
            }
            if ($this->user->getUserByUsername($username)) {

                Logger::warning(Logger::translate("logs.users.exists", ["username" => $username]));
                echo json_encode(["success" => false, "error" => Lang::get("responses.users.exists")]);
                return;
            }
            $this->user->create($data);
            Logger::info(Logger::translate("logs.users.create_success", ["username" => $username]));
            echo json_encode(["success" => true, 'message' => Lang::get('responses.users.create_success')]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_create", ['error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $user = $this->user->getUserById($id);
            if (!$user) {
                Logger::warning(Logger::translate("logs.users.not_found", ["id" => $id]));
                redirect("/admin/users");
            }
            return view("admin/users/edit.view.php", ["user" => $user]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_edit", ['id' => $id, 'error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function update(int $id): void
    {
        try {
            $data = $this->data();

            $username = $data["username"] ?? '';
            $email = $data["email"] ?? '';

            $validator = new Validator();

            if ($user = $this->user->getUserByUsername($username)) {
                if ((int)$user['user_id'] !== $id) {
                    $validator->addError('username', Lang::get('validator.username.exists'));
                }
            }

            if ($user = $this->user->getUserByEmail($email)) {
                if ((int)$user['user_id'] !== $id) {
                    $validator->addError('email', Lang::get('validator.email.exists'));
                }
            }

            $validator->validate($data, [
                'username' => v::notEmpty()->addRule($validator->noSpecialChars())->addRule(v::length(3, 20)),
                'email' => v::notEmpty()->addRule(v::email()),
            ]);

            if ($validator->hasErrors()) {
                Logger::warning(Logger::translate("logs.users.validation_failed", ['username' => $username, 'errors' => json_encode($validator->getErrors())]));
                echo json_encode([
                    'success' => false,
                    'errors' => $validator->getErrors()
                ]);
                return;
            }

            $this->user->update($id, $data);

            Logger::info(Logger::translate("logs.users.update_success", ["username" => $username]));
            echo json_encode([
                "success" => true,
                "message" => Lang::get("responses.users.update_success")
            ]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_update", ["id" => $id, "error" => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function updatePassword($userId)
    {
        try {
            $data = $this->data();
            $username = $this->user->getUserById($userId)['username'] ?? '';
            $password = $data["new_password"] ?? '';
            $v = new Validator;
            $v->validate($data, [
                'new_password' => v::notEmpty()->addRule($v->noSpecialChars())->addRule(v::length(3, 20)),
                'confirm_password' => v::equals($data["new_password"])->setTemplate(Lang::get("validator.password.equals")),
            ]);

            if ($v->hasErrors()) {
                Logger::warning(Logger::translate("logs.users.validation_failed", ['username' => $username, 'errors' => json_encode($v->getErrors())]));
                echo json_encode(["success" => false, 'errors' => $v->getErrors()]);
                return;
            }
            $this->user->updatePassword($userId, $password);

            Logger::info(Logger::translate("logs.users.update_success", ["username" => $username]));
            echo json_encode([
                "success" => true,
                "message" => Lang::get("responses.users.update_success")
            ]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_update", ["id" => $userId, 'error' => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }

    public function delete($userId)
    {
        try {
            $username = $this->user->getUserById($userId)['username'] ?? "";
            $this->user->delete($userId);
            Logger::info(Logger::translate("logs.users.delete_success", ["username" => $username]));
            echo json_encode(["success" => true, 'message' => Lang::get('responses.users.delete_success')]);
        } catch (\Throwable $e) {
            Logger::error(Logger::translate("logs.users.error_delete", ['id' => $userId, "error" => $e->getMessage()]));
            http_response_code(500);
            echo json_encode(["success" => false, 'error' => $e->getMessage()]);
        }
    }
}
