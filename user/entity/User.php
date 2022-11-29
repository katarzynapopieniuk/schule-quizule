<?php

class User {

    private $accountKey;
    private $accountType;
    private $email;
    private $id;
    private $name;
    private $surname;

    public function __construct($id) {
        $this->id = $id;
    }

    public function getAccountKey() {
        return $this->accountKey;
    }

    public function setAccountKey($accountKey) {
        $this->accountKey = $accountKey;
    }

    public function getAccountType() {
        return $this->accountType;
    }

    public function setAccountType($accountType) {
        $this->accountType = $accountType;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

}