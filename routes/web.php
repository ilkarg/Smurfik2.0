<?php

$router->get("/", "PageController::index");
$router->get("login", "PageController::login");
$router->get("registration", "PageController::registration");
$router->get("services", "PageController::services");
$router->get("portfolio", "PageController::portfolio");
$router->get("feedback", "PageController::feedback");
$router->get("contact", "PageController::contact");
$router->get("company", "PageController::company");

$router->get("admin", "PageController::admin");
$router->get("admin/services", "PageController::adminServices");
$router->get("admin/feedback", "PageController::adminFeedback");

$router->post("api/v1/login", "AuthController::login");
$router->post("api/v1/registration", "AuthController::registration");
$router->post("api/v1/logout", "AuthController::logout");
$router->post("api/v1/isAuthorized", "AuthController::isAuthorized");
$router->post("api/v1/isAdmin", "AdminController::isAdmin");
$router->post("api/v1/addFeedback", "FeedbackController::addFeedback");
$router->post("api/v1/getFeedbacks", "FeedbackController::getFeedbacks");
$router->post("api/v1/addOrder", "OrderController::addOrder");
$router->post("api/v1/getOrders", "OrderController::getOrders");