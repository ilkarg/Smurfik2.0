<?php

use \PHPExceptionHandler\ExceptionHandler;
use \PHPSystem\System;

class Model {
    public function getAttributeLabel(string $attribute) {
        return get_object_vars($this)[$attribute];
    }

    public function getAttributes() : array {
        return array_keys(get_object_vars($this));
    }

    public function getAttributeLabels() : array {
        return get_object_vars($this);
    }

    public function validate() : array {
        foreach($this->getAttributeLabels() as $attribute) {
            if (System::isNull($attribute)) {
                return array("status" => false, "message" => "Все поля должны быть заполнены");
            }
        }

        return array("status" => true, "message" => null);
    }
}