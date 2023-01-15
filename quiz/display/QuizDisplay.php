<?php

class QuizDisplay {

    const QUIZ_NOT_SOLVED_MESSAGE = "Quiz nierozwiązany";

    public static function display($quiz) {
        $quizId = $quiz->getId();
        ?>
        <form action="/schule-quizule/" method="post">
            <div class="quizName" id="<?php echo $quiz->getName()?>">
                <?php echo $quiz->getName()?>
            </div>
            <?php

            $questions = $quiz->getQuestions();
            if(is_array($questions)) {
                foreach($questions as $question) {
                    self::displayQuestion($question);
                }
            }

            if(isset($_POST['fillSharedQuiz']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
                self::addSaveAnswersHiddenPost();
            }
            ?>
            <input type="hidden" name="submittedQuizId" id="submittedQuizId" value="<?php print "$quizId" ?>"/>
            <input type="submit" name="sendAnswers" value="wyślij odpowiedzi">
        </form>
        <?php
    }

    public static function displayQuestion($question) {
        ?>
        <div class="quizQuestion" id="<?php echo $question->getQuestion()?>">
            <?php echo $question->getQuestion()?>
        </div>
        <?php

        $answers = $question->getAnswers();
        if(is_array($answers)) {
            foreach($answers as $answer) {
                self::displayAnswer($answer, $question->getId());
            }
        }
    }

    public static function displayAnswer($answer, $questionId) {
        ?>
        <div class="quizAnswer" id="<?php echo $answer->getId()?>">
            <input type="radio" id="<?php echo $answer->getId()?>" name="answerId:<?php echo $answer->getId()?>" value="<?php echo $answer->getId()?>">
                <?php echo $answer->getContent()?>
            <label for="html"></label><br>
        </div>
        <?php
    }

    public static function displayOwnerQuizOptions($quizId, \room\control\RoomClient $roomClient, UserClient $userClient, QuizClient $quizClient) {
        $rooms = $roomClient->getRoomsForSharedQuizWithId($quizId);
        echo "Quiz udostępniony pokojom: <br>";
        \room\display\RoomListDisplay::displayRoomList($rooms);

        $usersIds = $userClient->getUserForSharedQuizWithId($quizId);
        echo "Quiz udostępniony uczniom: <br>";

        $users = array();
        foreach ($usersIds as $userId) {
            $user = new User($userId);
            $userClient->setUserData($user);
            $users[] = $user;
        }
        foreach ($users as $user) {
            UserDataDisplay::displayUserSimpleData($user);
        }

        echo "Wyniki uczniów: <br>";
        foreach ($rooms as $room) {
            $usersInRoom = $roomClient->getUsersInRoom($room->getId(), $userClient);
            foreach ($usersInRoom as $user) {
                $users[] = $user;
            }
        }
        $quiz = $quizClient->getQuizzesById($quizId)[0];
        foreach ($users as $user) {
            echo $user->getName() . " " . $user->getSurname() . ": " . self::tryGetQuizResultIfAlreadyFilled($user->getId(), $quiz, $quizClient) . "<br>";
        }
        ?>

        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="sharedQuizId" id="submittedQuizId" value="<?php print "$quizId" ?>"/>
            <input type="submit" name="shareQuiz" value="udostępnij quiz">
        </form>
        <?php
    }

    public static function displayQuizOptionsForUser($quizId, $userId, UserClient $userClient, QuizClient $quizClient) {
        self::tryGetQuizWithIdResultIfAlreadyFilled($userId, $quizId, $quizClient);
    }

    public static function tryGetQuizResultIfAlreadyFilled($userId, Quiz $quiz, QuizClient $quizClient) {
        $correctAnswersIds = array();
        $answersIds = array();
        foreach($quiz->getQuestions() as $question) {
            $answers = $question->getAnswers();
            foreach ($answers as $answer) {
                $answersIds[] = $answer->getId();
                if($answer->getIsCorrect()) {
                    $correctAnswersIds[] = $answer->getId();
                }
            }
        }

        $answersIdsFilledByUser = $quizClient->getAnswersIdsFilledByUser($userId, $answersIds);

        if(count($answersIdsFilledByUser) <= 0) {
            return self::QUIZ_NOT_SOLVED_MESSAGE;
        }
        $correctFilledAnswersAmount = self::calculateCorrectFilledAnswersAmount($answersIdsFilledByUser, $correctAnswersIds);
        return "Wynik: " . $correctFilledAnswersAmount . "/" . count($correctAnswersIds);
    }

    private static function tryGetQuizWithIdResultIfAlreadyFilled($userId, $quizId, QuizClient $quizClient) {
        $quizzes = $quizClient->getQuizzesById($quizId);
        if(count($quizzes) > 0) {
            $quiz = $quizzes[0];
            $quizResult = self::tryGetQuizResultIfAlreadyFilled($userId, $quiz, $quizClient);
            echo $quizResult . "<br/>";
            self::displayFillQuizOption($quizId);
        }
    }

    private static function calculateCorrectFilledAnswersAmount(array $answersIdsFilledByUser, array $correctAnswersIds): int {
        $filledCorrectAnswerCount = 0;
        foreach($answersIdsFilledByUser as $filledAnswerId) {
            if(in_array($filledAnswerId, $correctAnswersIds)) {
                $filledCorrectAnswerCount++;
            }
        }
        return $filledCorrectAnswerCount;
    }

    private static function displayFillQuizOption($quizId) {
        ?>
        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="current_quiz" id="current_quiz" value="<?php print "$quizId" ?>"/>
            <input type="submit" name="fillSharedQuiz" value="wypełnij quiz">
        </form>
        <?php
    }

    private static function addSaveAnswersHiddenPost() {
        ?>
        <input type="hidden" name="saveFilledAnswers" id="saveFilledAnswers""/>
        <?php
    }
}