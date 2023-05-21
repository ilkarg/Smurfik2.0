<?php

class AdminController {
	public static function isAdmin() {
        session_start();
        if (isset($_SESSION["user"]) && $_SESSION["user"]->login == "admin") {
            echo json_encode(array("response" => "admin"));
        } else {
            echo json_encode(array("response" => "not_admin"));
        }
    }
}