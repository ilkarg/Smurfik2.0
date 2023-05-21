<?php

use PHPSystem\System;
use PHPHash\Hash;

class QueryController {
	public static function loginQuery(string $login, string $password) {
        session_start();
        if (isset($_SESSION["user"])) {
            return json_encode(array("response" => "Вы уже находитесь в аккаунте"));
        }
        global $orm;
        $orm->connect();
        $user = R::find("users", "login = ? AND password = ?", [$login, Hash::sha256($password, "", 1)]);
        $user = $user[array_key_first($user)];
        if ($user == null) {
            return json_encode(array("response" => "Неверные логин или пароль"));
        }
        $_SESSION["user"] = $user;
        return json_encode(array("response" => "Вы успешно вошли в аккаунт"));
    }

    public static function registrationQuery(string $login, string $password) {
        session_start();
        if (isset($_SESSION["user"])) {
            return json_encode(array("response" => "Вы уже находитесь в аккаунте"));
        }
        global $orm;
        $orm->connect();
        $user = R::dispense("users");
        $user->login = $login;
        $user->password = Hash::sha256($password, "", 1);
        try {
            R::store($user);
            $_SESSION["user"] = $user;
        } catch (RedBeanPHP\RedException\SQL $except) {
            if (System::startsWith($except->getMessage(), "SQLSTATE[23000]: Integrity constraint violation")) {
                return json_encode(array("response" => "User already exists"));
            }
        }
        return json_encode(array("response" => "OK"));
    }

    public static function addOrderQuery(string $name, string $phone, string $service) {
        global $orm;
        $orm->connect();
        $order = R::dispense("orders");
        $order->name = $name;
        $order->phone = $phone;
        $order->service = $service;
        R::store($order);
        return json_encode(array("response" => "Заявка успешно отправлена"));
    }

    public static function addFeedbackQuery(string $name, string $phone, string $question) {
        global $orm;
        $orm->connect();
        $feedback = R::dispense("feedbacks");
        $feedback->name = $name;
        $feedback->phone = $phone;
        $feedback->question = $question;
        R::store($feedback);
        return json_encode(array("response" => "Обращение успешно отправлено"));
    }

    public static function getFeedbacksQuery() {
        global $orm;
        $orm->connect();
        $feedbacks = R::findAll("feedbacks");
        if ($feedbacks == null) {
            return json_encode(array("response" => "Обращения не найдены"));
        }
        return json_encode($feedbacks);
    }

    public static function getOrdersQuery() {
        global $orm;
        $orm->connect();
        $orders = R::findAll("orders");
        if ($orders == null) {
            return json_encode(array("response" => "Заказы не найдены"));
        }
        return json_encode($orders);
    }
}