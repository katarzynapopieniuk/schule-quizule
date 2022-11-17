<?php

class QuizClient {

    function getQuizzesByCategory($category) {
        $databaseConnection = DatabaseClient::openConnection();
        $query = "SELECT * from quiz where category = '$category'";

        $result = mysqli_query($databaseConnection, $query);
        $quizzes = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $category = $row["category"];
                $isPublic = $row["isPublic"];
                $name = $row["name"];
                $id = $row["id"];
                $quiz = new Quiz($category, $isPublic, $name, $id, null);
                $quizzes[] = $quiz;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $quizzes;
    }

    function getQuizzesById($quizId) {
        $databaseConnection = DatabaseClient::openConnection();
        $query = "SELECT * from quiz where id = '$quizId'";

        $result = mysqli_query($databaseConnection, $query);
        $quizzes = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $category = $row["category"];
                $isPublic = $row["isPublic"];
                $name = $row["name"];
                $id = $row["id"];
                $quiz = new Quiz($category, $isPublic, $name, $id, null);
                $questions = $this->getQuizQuestions($quiz, $databaseConnection);
                $quiz->setQuestions($questions);
                $quizzes[] = $quiz;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $quizzes;
    }

    function getQuizQuestions($quiz, $databaseConnection) {
        $id = $quiz->getId();
        $quizQuestionQuery = "SELECT * from quiz_question where quizId = '$id'";
        $result = mysqli_query($databaseConnection, $quizQuestionQuery);
        $questions = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $question = $row["question"];
                $id = $row["id"];
                $answers = $this->getQuestionAnswers($id, $databaseConnection);
                $quizQuestion = new Question($question, $id, $answers);
                $questions[] = $quizQuestion;
            }
        }
        return $questions;
    }

    function getQuestionAnswers($questionId, $databaseConnection) {
        $questionAnswersQuery = "SELECT * from answer where questionId = $questionId";
        $result = mysqli_query($databaseConnection, $questionAnswersQuery);
        $answers = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $content= $row["content"];
                $isCorrect = $row["isCorrect"];
                $id = $row["id"];
                $answer = new Answer($content, $isCorrect, $id, false);
                $answers[] = $answer;
            }
        }
        return $answers;
    }
}