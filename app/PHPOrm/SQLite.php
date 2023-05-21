<?php

include __DIR__ . '/driver/Redbean.php';

use \PHPExceptionHandler\ExceptionHandler;

class SQLite {
    protected string $dbname;

    public function __construct(string $dbname) {
        $this->dbname = $dbname;
    }

    public function overrideConnectData(string $dbname) {
        $this->dbname = $dbname;
    }

    private function generateConnectionStr() {
        return "sqlite:".$this->dbname;
    }

    public function connect() {
        $connection_str = $this->generateConnectionStr();
        R::setup($connection_str);

        if (!R::testConnection()) {
            ExceptionHandler::generateError("Не удалось установить соединение с базой данных SQLite");
        }
    }
}
