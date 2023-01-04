<?php

class QuizDisplay {

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
            <label for="html">HTML</label><br>
        </div>
        <?php
    }

    public static function displayOwnerQuizOptions($quizId, \room\control\RoomClient $roomClient, UserClient $userClient) {
        $roomsIds = $roomClient->getRoomsForSharedQuizWithId($quizId);
        echo "Quiz udostępniony pokojom:";
        \room\display\RoomListDisplay::displayRoomList($roomsIds);

        $usersIds = $userClient->getUserForSharedQuizWithId($quizId); // todo
        echo "Quiz udostępniony uczniom:";

        $users = array();
        foreach ($usersIds as $userId) {
            $user = new User($userId);
            $userClient->setUserData($user);
            $users[] = $user;
        }
        foreach ($users as $user) {
            UserDataDisplay::displayUserSimpleData($user);
            if(self::isLoggedUserTeacher()) {
                ///UserDataDisplay::displayUnshareQuizFromUserButton($quizId, $user->getId()); TODO
            }
        }

        ?>

        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="sharedQuizId" id="submittedQuizId" value="<?php print "$quizId" ?>"/>
            <input type="submit" name="shareQuiz" value="udostępnij quiz">
        </form>
        <?php
    }
}