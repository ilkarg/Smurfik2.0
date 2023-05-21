<?php

namespace PHPTemplater;

include __DIR__ . '/../PHPExceptionHandler/ExceptionHandler.php';
include __DIR__ . '/../PHPSystem/System.php';

use PHPExceptionHandler\ExceptionHandler;
use PHPSystem\System;

class Template {
    protected string $template;

    public function __construct($template) {
        if (System::endsWith($template, ".html")) {
            $template = file_get_contents($template);
        }

        if (System::isNull($template)) {
            ExceptionHandler::generateError("Не удалось установить шаблон страницы");
        }

        $this->template = $template;
    }

    public function getTemplate() {
        return $this->template;
    }

    public function setTemplate($template) {
        if (System::endsWith($template, ".html")) {
            $template = file_get_contents($template);
        }

        if (System::isNull($template)) {
            ExceptionHandler::generateError("Не удалось установить шаблон страницы");
        }

        $this->template = $template;
    }

    public function generatePage($content) {
        if ($content == '') {
            $content = ' ';
        }
        
        if (System::isNull($content)) {
            ExceptionHandler::generateError("Не удалось сгенерировать страницу");
        }

        if (System::endsWith($content, ".html")) {
            $content = file_get_contents($content);
        }

        $page = str_replace("[content]", $content, $this->template);

        return $page;
    }
}
