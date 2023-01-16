<?php

session_start();

function displayQuiz(Quiz $quiz){
    ?><form action="/schule-quizule/" method="post">
                    "<div class="quizName" id="<?php echo $quiz->getName()?>">
                        <?php echo $quiz->getName()?>
                    </div>"

                    <input type="hidden" name="current_quiz" id="current_quiz" value="<?php echo $quiz->getId() ?>"/>
                    <input type="submit" name="sendAnswers" value="Wypełnij">
                </form>; <?php
}

require_once("../quiz/entity/Quiz.php");
require_once("../quiz/entity/Answer.php");
require_once("../quiz/entity/Category.php");
require_once("../quiz/entity/Question.php");
require_once("../quiz/display/CategoryDisplay.php");
require_once("../quiz/display/QuizListDisplay.php");
require_once("../quiz/display/QuizDisplay.php");
require_once("../quiz/control/QuizClient.php");
require_once("../quiz/control/QuizResultCalculator.php");
require_once("../quiz/control/QuizSharer.php");
require_once("../database/control/DatabaseClient.php");

//Status przekroczonego czasu sesji jeśli widnieje przenosi od razu do pliku docelowego
if ((isset($_SESSION['time_out'])) && ($_SESSION['time_out'] == true)) {
    header('Location: time_out.php');
    exit();
}
//Status przeładowania próbami zalogowania jeśli widnieje w sesji przenosi odrazu do pliku docelowego
if ((isset($_SESSION['overload'])) && ($_SESSION['overload'] == true)) {
    header('Location: attemps_overload.php');
    exit();
}

$output = '';
$foundQuizzes=array();

if (isset($_POST['search'])) {
    $searchq = $_POST['search'];

    if (strlen($searchq) < 1) {
        $_SESSION['e_search'] = "";
    }

    require_once "../database/control/DatabaseClient.php";

    try {
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {
            $searchForQuiz = "SELECT * FROM `quiz` WHERE `name` LIKE :searchq AND `isPublic`=1";
            $searchForQuizResult = $connect->prepare($searchForQuiz);
            $searchForQuizResult->bindValue(':searchq', '%' . $searchq . '%', PDO::PARAM_STR);

            $searchForQuizResult->execute();
            $how_many_quiz = $searchForQuizResult->rowCount();
            $fetch = $searchForQuizResult->fetchAll();
            if (isset($_SESSION['e_search']) || $how_many_quiz == 0) {
                unset($_SESSION['e_search']);
                $output = '<span style="color:red">Nothing was searched </span>';
            } else {
                foreach ($fetch as $row) {
                    $quiz = $row['name'];
                    $quizId = $row['id'];
                    $foundQuizzes[] = new Quiz(null, null, $quiz, $quizId, null);
                }
            }
        }
        $connect = null;
        $searchForQuiz = null;
        $searchForQuizResult = null;
    } catch (PDOException $exception) {
        echo $exception->getMessage();
        echo '<span style="color:red;">Server error. Sorry for that try later!</span>';
        echo '<br/> Developer information: ' . $exception;
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible"
          content="IE=edge">
    <meta name="viewport" content="width=devic-width,
    initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="../logging/login_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <h2 class="card-title text-center">Search your quizes</h2>
            <div class="card-body py-md-4">
                <form _lpchecked="1" action="searcher.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search" placeholder="Wyszukaj quizy" name="search">
                        <button type="submit" name="save_radio" class="btn btn-primary">🔍</button>
                    </div>
                </form>
                <?php foreach ($foundQuizzes as $quiz) {
                     displayQuiz($quiz);
                     }
                     print $output;
                ?>

            </div>

        </div>
    </div>
</div>
</div>
</body>
</html>
