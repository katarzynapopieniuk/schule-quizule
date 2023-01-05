<?php

class QuizListDisplay {

    public static function display($quizList) {
        if(is_array($quizList)) {
            foreach($quizList as $quiz): ?>
                <div class="quizName" id="<?php echo $quiz->getName()?>" onclick="setCurrentQuizPOST('<?php echo $quiz->getId()?>')">
                    <?php echo $quiz->getName()?>
                </div>
            <?php endforeach;
        }
    }

    public static function displayQuizListAsOwner($quizList) {
        if(is_array($quizList)) {
            foreach($quizList as $quiz):
                ?>
                <div class="quizName" id="<?php echo $quiz->getName()?>" onclick="setCurrentQuizAsOwnerPOST('<?php echo $quiz->getId()?>')">
                    <?php echo $quiz->getName()?>
                </div>
            <?php endforeach;
        }
    }
}