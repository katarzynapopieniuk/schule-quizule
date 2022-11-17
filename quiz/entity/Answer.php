<?php

class Answer {
    private $content;
    private $isCorrect;
    private $isMarked;
    private $id;

    public function __construct($content, $isCorrect, $id, $isMarked) {
        $this->content = $content;
        $this->isCorrect = $isCorrect;
        $this->id = $id;
        if($isMarked == null) {
            $this->isMarked = false;
        } else {
            $this->isMarked = $isMarked;
        }
    }

    public function getContent() {
        return $this->content;
    }

    public function getIsCorrect() {
        return $this->isCorrect;
    }

    public function getId() {
        return $this->id;
    }

    public function getIsMarked() {
        return $this->isMarked;
    }

    public function setMarked() {
        $this->isMarked = true;
    }
}