<?php

class Question {

    private $question;
    private $answers;
    private $id;

    public function __construct($question, $id, $answers) {
        $this->question = $question;
        $this->id = $id;
        if($answers == null) {
            $this->answers = array();
        } else {
            $this->answers = $answers;
        }
    }

    public function getQuestion() {
        return $this->question;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function getId() {
        return $this->id;
    }
}