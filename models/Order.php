<?php

class Order extends Model {
	public string $name;
    public string $phone;
    public string $service;

    public function __construct(string $name, string $phone, string $service) {
        $this->name = $name;
        $this->phone = $phone;
        $this->service = $service;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPhone() : string {
        return $this->phone;
    }

    public function getService() : string {
        return $this->service;
    }
}