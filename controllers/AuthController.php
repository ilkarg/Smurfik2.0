<?php

include __DIR__ . "/../models/User.php";

class AuthController {
	public static function login() {
        global $router;
        $data = $router->getPostRouteData();
        if ($data != null) {
            $user_model = new User($data["login"], $data["password"]);
            echo QueryController::loginQuery($user_model->login, $user_model->password);
        } else {
            echo json_encode(array("response" => "Данные не дошли или неверные имена полей"));
        }
    }

    public static function registration() {
        global $router;
        $data = $router->getPostRouteData();
        if ($data != null) {
            if ($data["password"] == $data["repeatPassword"]) {
                $user_model = new User($data["login"], $data["password"]);
                $result = $user_model->validate();
                if ($result["status"]) {
                    echo QueryController::registrationQuery($user_model->login, $user_model->password);
                } else {
                    echo json_encode(array("response" => $result["message"]));
                }
            } else {
                echo json_encode(array("response" => "Password and repeat password do not match"));
            }
        } else {
            echo json_encode(array("response" => "Данные не дошли или неверные имена полей"));
        }
    }

    public static function logout() {
        session_start();
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
            echo json_encode(array("response" => "Вы успешно вышли из аккаунта"));
        } else {
            echo json_encode(array("response" => "Вы и так не находитесь в аккаунте"));
        }
    }

    public static function isAuthorized() {
        session_start();
        echo json_encode(array("response" => isset($_SESSION["user"])));
    }
}