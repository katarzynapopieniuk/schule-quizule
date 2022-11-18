<?php

class Quiz {

    private $category;
    private $isPublic;
    private $name;
    private $questions;
    private $id;

    public function __construct($category, $isPublic, $name, $id, $questions) {
        $this->category = $category;
        $this->isPublic = $isPublic;
        $this->name = $name;
        $this->id = $id;
        if($questions == null) {
            $this->questions = array();
        } else {
            $this->questions = $questions;
        }
    }

    public function getCategory() {
        return $this->category;
    }

    public function getIsPublic() {
        return $this->isPublic;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }

    public function getId() {
        return $this->id;
    }

    public function fill() {

    }

    public function modify() {

    }

    public function remove() {

    }

    public function __toString() {
        return "Category: $this->category, name:  $this->name, isPublic: $this->isPublic, id: $this->id";
    }


}

?>