<?php

namespace PHPExceptionHandler;

use Exception;

class ExceptionHandler {
    private static string $html_error_page = "none";

    /**
     * @throws \Exception
     */
    public static function generateError(string $error_message) {
        if (self::$html_error_page == "none") {
            echo "<h3 class='php-exception-handler-error'>Ошибка: $error_message</h3>";
        } else {
            $html = str_replace("[error]", "<h3 class='php-exception-handler-error'>Ошибка: $error_message</h3>", self::$html_error_page);
            echo $html;
        }

        throw new Exception("$error_message");
    }

    public static function setCustomErrorPage(string $html) {
        self::$html_error_page = $html;
    }
}