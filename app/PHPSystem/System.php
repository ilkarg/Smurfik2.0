<?php

namespace PHPSystem;

class System {
    public static function isNull($value) {
        if (gettype($value) == "string") {
            return $value == null || strtolower($value) == 'null';
        } else {
            return $value == null;
        }
    }
    
    public static function addCors() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            }

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            }

            exit(0);
        }
    }
    
    public static function startsWith(string $haystack, string $needle) {
        $haystack_len = strlen($haystack);
        $needle_len = strlen($needle);
        
        if ($haystack_len < $needle_len) {
            return false;
        }
        
        $str = substr($haystack, 0, $needle_len);
        return $str == $needle;
    }
    
    public static function endsWith(string $haystack, string $needle) {
        $haystack_len = strlen($haystack);
        $needle_len = strlen($needle);
        
        if ($haystack_len < $needle_len) {
            return false;
        }
        
        $str = substr($haystack, $haystack_len - $needle_len, $haystack_len);
        return $str == $needle;
    }

    public static function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
