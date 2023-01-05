<?php
session_start();
//Status zalogowany jeśli  nie widnieje w sesji przenosi odrazu do pliku docelowego
if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
    exit();
}
//Zmienne po udanej walidacji dodania quizu, pytania i odpowiedzi jeśli nie istnieją w sesji przenosi do pliku tworzenia quizu
if ((!isset($_SESSION['quiz_add_ended'])) && (!isset($_SESSION['question_add_ended'])) && (!isset($_SESSION['answer_add_ended']))) {
    header('Location: create_quiz.php');
    exit();
}
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
$loggedId = $_SESSION['Id'];
$quizId = $_SESSION['quiz_id'];
$questionId = $_SESSION['question_id'];
require_once "../database/control/DatabaseClient.php";
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $connect = DatabaseClient::createPDO();
    if (!$connect) {
        die("Fatal Error: Connection Failed!");
    } else {
        $showYourQuiz = "SELECT `name` FROM `quiz` WHERE `owner_id`='$loggedId'";
        $showYourQuizResult = $connect->prepare($showYourQuiz);
        $showYourQuizResult->execute();
        while ($fetch = $showYourQuizResult->fetch(PDO::FETCH_ASSOC)) {
            $quizName = $fetch['name'];
        }

        $QuestionList = "SELECT id, question FROM  `quiz_question` WHERE  `quizId`='$quizId'";
        $QuestionListResult = $connect->prepare($QuestionList);
        $QuestionListResult->execute();
        $SumRowQuestion = $QuestionListResult->rowCount();
        $ALLQuestionList = $QuestionListResult->fetchAll(PDO::FETCH_ASSOC);
        $result_data = [];
        $SumAnswerForeachQuestion = [];

        for ($i = $ALLQuestionList[0]['id']; $i < ($questionId + $SumRowQuestion - 1); $i++) {
            $showYourAnswer = "SELECT  `questionId`, `content` FROM `answer` WHERE `questionId`='$i'";
            $showYourAnswerResult = $connect->prepare($showYourAnswer);
            $showYourAnswerResult->execute();
            $SumAnswerForeachQuestion[$i] = $showYourAnswerResult->rowCount();
            $result_data_from1Question = $showYourAnswerResult->fetchAll(PDO::FETCH_ASSOC);

            $result_data = array_merge($result_data, $result_data_from1Question);
        }
        $ids = array();
    }

} catch (PDOException $exception) {
    echo $exception->getMessage();
    echo '<span style="color:red;">Server error. Sorry for that try later!</span>';
    echo '<br/> Developer information: ' . $exception;
    die();
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
    <title>Quiz Created</title>
    <link rel="stylesheet" href="quiz_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<br>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Your Quiz</h2>
                <div class="card-body py-md-4">
                    <div class="form-group">
                        <table>
                            <tr>
                                <th> <?php echo $quizName; ?></th>
                            </tr>
                            <tr>
                                <th> Pytania</th>
                                <th> Odpowiedzi</th>
                            </tr>
                            <tr>
                                <td>
                                    <tbody>
                                    <?php
                                    $i = $ALLQuestionList[0]['id'];
                                    foreach ($ALLQuestionList as $questionValue) {
                                        echo "<tr><td rowspan='" . ($SumAnswerForeachQuestion[$i] + 2) . "' style='border: 2px solid black;'> " . $questionValue['question'] . "</td><tr>";
                                        foreach ($result_data as $data) {
                                            if ($data['questionId'] == $i)
                                                echo "<tr><td>" . $data['content'] . "</td></tr>";
                                        }
                                        $i++;
                                    }
                                    ?>
                                    </tbody>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="add_question.php?Quiz_ID=<?php echo $quizId; ?>">Want add more questions?</a>
                            <br>
                            <!--                            <a href="#"> Want edit quiz?</a> to do-->
                            <br>
                            <a href="../index.php">All clear? Go to main page</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>