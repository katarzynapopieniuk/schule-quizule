<?php

namespace room\entity;
class Room {

    private $id;
    private $name;
    private $teacherId;

    public function __construct($id, $name, $teacherId) {
        $this->id = $id;
        $this->name = $name;
        $this->teacherId = $teacherId;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getTeacherId() {
        return $this->teacherId;
    }
}