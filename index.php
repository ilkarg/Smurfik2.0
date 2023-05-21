<?php

// Подключение системных библиотек
include __DIR__ . '/app/PHPRouter/Router.php';
include __DIR__ . '/app/PHPOrm/SQLite.php';
include __DIR__ . '/app/PHPTemplater/Template.php';
include __DIR__ . '/app/PHPView/View.php';
include __DIR__ . '/app/PHPRequester/Request.php';
include __DIR__ . "/app/PHPHash/Hash.php";
include __DIR__ . '/app/PHPModel/Model.php';

// Подключение системных пространств имен
use PHPRouter\Router;

// Создание системных объектов
$router = new Router();
$orm = new SQLite(__DIR__ . "/db/smurfik.db");

// Подключение контроллеров
include __DIR__ . '/controllers/PageController.php';
include __DIR__ . '/controllers/AuthController.php';
include __DIR__ . '/controllers/AdminController.php';
include __DIR__ . '/controllers/FeedbackController.php';
include __DIR__ . '/controllers/OrderController.php';
include __DIR__ . '/controllers/QueryController.php';

// Подключение файла с маршрутами
include __DIR__ . '/routes/web.php';

$router->exec();
