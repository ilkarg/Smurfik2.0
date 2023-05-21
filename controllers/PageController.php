<?php

use PHPTemplater\Template;
use PHPView\View;

class PageController {
    public static function index() {
        $template = new Template(__DIR__ . "/../pages/index.html");
        echo View::createFromTemplate($template);
    }

    public static function login() {
        $template = new Template(__DIR__ . "/../pages/login.html");
        echo View::createFromTemplate($template);
    }

    public static function registration() {
        $template = new Template(__DIR__ . "/../pages/registration.html");
        echo View::createFromTemplate($template);
    }

    public static function services() {
        $template = new Template(__DIR__ . "/../pages/services.html");
        echo View::createFromTemplate($template);
    }

    public static function portfolio() {
        $template = new Template(__DIR__ . "/../pages/portfolio.html");
        echo View::createFromTemplate($template);
    }

    public static function feedback() {
        $template = new Template(__DIR__ . "/../pages/feedback.html");
        echo View::createFromTemplate($template);
    }

    public static function contact() {
        $template = new Template(__DIR__ . "/../pages/contact.html");
        echo View::createFromTemplate($template);
    }

    public static function company() {
        $template = new Template(__DIR__ . "/../pages/company.html");
        echo View::createFromTemplate($template);
    }

    public static function admin() {
        $template = new Template(__DIR__ . "/../pages/admin/admin.html");
        echo View::createFromTemplate($template);
    }

    public static function adminServices() {
        $template = new Template(__DIR__ . "/../pages/admin/services.html");
        echo View::createFromTemplate($template);
    }

    public static function adminFeedback() {
        $template = new Template(__DIR__ . "/../pages/admin/feedback.html");
        echo View::createFromTemplate($template);
    }
}