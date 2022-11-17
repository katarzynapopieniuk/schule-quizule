<?php

class QuizResultCalculator {

    static function calculateQuizResult($posts, $quizClient) {
        $quizId = $posts['submittedQuizId'];
        $correctAnswerAmount = self::calculateCorrectAnswerAmount($posts, $quizClient);
        $allQuestionAmount = self::getAllQuestionAmount($quizId, $quizClient);
        return $correctAnswerAmount . '/' . $allQuestionAmount;
    }

    static function calculateCorrectAnswerAmount($posts, $quizClient) {
        $correctAnswerCounter = 0;
        foreach($posts as $key=>$value) {
            if (strpos($key, 'answerId') === 0) {
                $answerId = explode(':', $key)[1];
                if(self::isCorrect($answerId, $quizClient)) {
                    $correctAnswerCounter++;
                }
            }
        }
        return $correctAnswerCounter;
    }

    private static function isCorrect($answerId, $quizClient) {
        return $quizClient->isCorrectAnswer($answerId, $quizClient);
    }

    private static function getAllQuestionAmount($quizId, $quizClient) {
        return $quizClient->getAllQuestionAmount($quizId, $quizClient);
    }
}