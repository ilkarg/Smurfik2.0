<?php

include __DIR__ . '/driver/Redbean.php';

use \PHPExceptionHandler\ExceptionHandler;

class MySQL {
    protected string $host;
    protected string $user;
    protected string $password;
    protected string $dbname;
    protected int $port;

    public function __construct(string $host, string $user, string $password, string $dbname, int $port = 3306) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
    }

    public function overrideConnectData(string $host, string $user, string $password, string $dbname, int $port = 3306) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->port = $port;
    }

    public function overrideConnectHost(string $host) {
        $this->host = $host;
    }

    public function overrideConnectUser(string $user) {
        $this->user = $user;
    }

    public function overrideConnectPassword(string $password) {
        $this->password = $password;
    }

    public function overrideConnectDbname(string $dbname) {
        $this->dbname = $dbname;
    }

    public function overrideConnectPort(string $port) {
        $this->port = $port;
    }

    private function generateConnectionStr() {
        return "mysql:host=".$this->host.";port=".$this->port.";dbname=".$this->dbname;
    }

    public function connect() {
        $connection_str = $this->generateConnectionStr();
        R::setup($connection_str, $this->user, $this->password, true);

        if (!R::testConnection()) {
            ExceptionHandler::generateError("Не удалось установить соединение с базой данных MySQL");
        }
    }
}
