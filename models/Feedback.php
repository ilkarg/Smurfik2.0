<?php

class Feedback extends Model {
	public string $name;
    public string $phone;
    public string $question;

    public function __construct(string $name, string $phone, string $question) {
        $this->name = $name;
        $this->phone = $phone;
        $this->question = $question;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPhone() : string {
        return $this->phone;
    }

    public function getQuestion() : string {
        return $this->question;
    }
}