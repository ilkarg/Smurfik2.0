<?php

namespace PHPRouter;

use PHPExceptionHandler\ExceptionHandler;

include "Route.php";

class Router {
    private array $route_array = array();

    public function __construct() {
        error_reporting(0);
    }

    public function isEmpty(string $value) : bool {
        return strlen(trim($value)) == 0;
    }

    public function routeMethodExists(string $route, string $method) : bool {
        return $this->findRouteMethod($route, $method) != null;
    }

    public function get(string $route, $func) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if ($this->routeMethodExists($route, "GET")) {
            ExceptionHandler::generateError("Маршрут /$route с методом GET использован больше одного раза");
        }

        $this->route_array[$route]["GET"] = new Route($route, "GET", $func);
    }

    public function overrideGetRoute(string $old_route, string $new_route) {
        if ($this->isEmpty($old_route) || $this->isEmpty($new_route)) {
            ExceptionHandler::generateError("Старый и новый маршрут не должны быть пустыми");
        }

        if (!$this->routeMethodExists($old_route, "GET")) {
            ExceptionHandler::generateError("Маршрут /$old_route с методом GET нельзя переопределить, он не существует");
        }

        if ($this->routeMethodExists($new_route, "GET")) {
            ExceptionHandler::generateError("Маршрут /$new_route с методом GET нельзя переопределить, он уже существует");
        }

        $func = $this->route_array[$old_route]["GET"]->action;
        unset($this->route_array[$old_route]["GET"]);
        $this->route_array[$new_route]["GET"] = new Route($new_route, "GET", $func);
    }

    public function overrideGetRouteAction(string $route, $func) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if (!$this->routeMethodExists($route, "GET")) {
            ExceptionHandler::generateError("Маршрут /$route с методом GET нельзя переопределить, он не существует");
        }

        $this->route_array[$route]["GET"]->action = $func;
    }

    public function getGetRouteData($uri) {
        $request_data = null;

        if (strpos($uri, "/?") !== false) {
            $request_data = parse_url($uri, PHP_URL_QUERY);
            parse_str($request_data, $request_data);
        }

        return $request_data;
    }

    public function removeGet(string $route) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if (!$this->routeMethodExists($route, "GET")) {
            ExceptionHandler::generateError("Маршрут /$route с методом GET нельзя удалить, он не существует");
        }

        if ($this->route_array[$route]["POST"] != null) {
            unset($this->route_array[$route]["GET"]);
        } else {
            unset($this->route_array[$route]);
        }
    }

    public function post(string $route, $func) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if ($this->routeMethodExists($route, "POST")) {
            ExceptionHandler::generateError("Маршрут /$route с методом POST использован больше одного раза");
        }

        $this->route_array[$route]["POST"] = new Route($route, "POST", $func);
    }

    public function overridePostRoute(string $old_route, string $new_route) {
        if ($this->isEmpty($old_route) || $this->isEmpty($new_route)) {
            ExceptionHandler::generateError("Старый и новый маршрут не должны быть пустыми");
        }

        if (!$this->routeMethodExists($old_route, "POST")) {
            ExceptionHandler::generateError("Маршрут /$old_route с методом POST нельзя переопределить, он не существует");
        }

        if ($this->routeMethodExists($new_route, "POST")) {
            ExceptionHandler::generateError("Маршрут /$new_route с методом POST нельзя переопределить, он уже существует");
        }

        $func = $this->route_array[$old_route]["POST"]->action;
        unset($this->route_array[$old_route]["POST"]);
        $this->route_array[$new_route]["POST"] = new Route($new_route, "POST", $func);
    }

    public function overridePostRouteAction(string $route, $func) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if (!$this->routeMethodExists($route, "POST")) {
            ExceptionHandler::generateError("Маршрут /$route с методом POST нельзя переопределить, он не существует");
        }

        $this->route_array[$route]["POST"]->action = $func;
    }

    public function getPostRouteData() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($data == null) {
            return $_POST;
        } else {
            return $data;
        }
    }

    public function removePost(string $route) {
        if ($this->isEmpty($route)) {
            ExceptionHandler::generateError("Маршрут не должен быть пустым");
        }

        if (!$this->routeMethodExists($route, "POST")) {
            ExceptionHandler::generateError("Маршрут /$route с методом POST нельзя удалить, он не существует");
        }

        if ($this->route_array[$route]["GET"] != null) {
            unset($this->route_array[$route]["POST"]);
        } else {
            unset($this->route_array[$route]);
        }
    }

    public function findRouteMethod(string $route, string $method) {
        return $this->route_array[$route][$method];
    }

    public function exec() {
        $uri = preg_replace("/\//", "", $_SERVER["REQUEST_URI"], 1);
        $method = $_SERVER["REQUEST_METHOD"];

        if ($uri == null) {
            $uri = "/";
        }
        
        if (strpos($uri, "/?") !== false && $method == "GET") {
            $uri = stristr($uri, "/?", true);
        } else if (strpos($uri, "/?") !== false && $method != "GET") {
            ExceptionHandler::generateError("<h3>Маршрут /$uri не поддерживает GET запросы</h3>");
        }

        if (!array_key_exists($uri, $this->route_array)) {
            ExceptionHandler::generateError("<h3>Маршрут /$uri не найден</h3>");
        }

        if (!$this->routeMethodExists($uri, $method)) {
            ExceptionHandler::generateError("<h3>Маршрут /$uri не поддерживает тип запроса $method</h3>");
        }

        if ($uri != $this->route_array[$uri][$method]->uri) {
            header('Location: ' . $this->route_array[$uri][$method]->uri);
        }
        call_user_func($this->route_array[$uri][$method]->action);
    }
}
