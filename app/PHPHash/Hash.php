<?php

namespace PHPHash;

use \PHPExceptionHandler\ExceptionHandler;
use \PHPSystem\System;

class Hash {
    public static function sha256(string $value, string $salt, int $count) {
        if ($count <= 0) {
            return;
        }

        if (System::isNull($value)) {
            ExceptionHandler::generateError("Невозможно сгенерировать хеш из пустого значения");
        }

        $hash = $value;

        if (!System::isNull($salt)) {
            $hash .= $salt;
        }

        for ($i = 0; $i < $count; $i++) {
            $hash = hash("sha256", $hash);
        }

        return $hash;
    }

    public static function md5(string $value, string $salt, int $count) {
        if ($count <= 0) {
            return;
        }

        if (System::isNull($value)) {
            ExceptionHandler::generateError("Невозможно сгенерировать хеш из пустого значения");
        }

        $hash = $value;

        if (!System::isNull($salt)) {
            $hash .= $salt;
        }

        for ($i = 0; $i < $count; $i++) {
            $hash = hash("md5", $hash);
        }

        return $hash;
    }
}
