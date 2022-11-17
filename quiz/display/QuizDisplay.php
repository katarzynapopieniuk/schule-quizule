<?php

class QuizDisplay {

    public static function display($quiz) {
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
            <input type="submit" value="wyślij odpowiedzi">
        </form>
        <?php
    }

    static function displayQuestion($question) {
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

    static function displayAnswer($answer, $questionId) {
        ?>
        <div class="quizAnswer" id="<?php echo $answer->getId()?>">
            <input type="radio" id="<?php echo $answer->getContent()?>" name="<?php echo $questionId?>" value="<?php echo $answer->getId()?>">
                <?php echo $answer->getContent()?>
            <label for="html">HTML</label><br>
        </div>
        <?php

    }
}