<?php

class User extends Model {
    public string $login;
    public string $password;

    public function __construct(string $login, string $password) {
        $this->login = $login;
        $this->password = $password;
    }

    public function getLogin() : string {
        return $this->login;
    }

    public function getPassword() : string {
        return $this->password;
    }
}
