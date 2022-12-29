<?php
session_start();
$quizId=$_SESSION['quiz_id'];
$questionName=$_SESSION['question_name'];

//Status zalogowany jeśli  nie widnieje w sesji przenosi odrazu do pliku docelowego
if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
    exit();
}
if (!isset($_GET['Question_ID'])) {
    echo '<a style="color:red" href="add_question.php">Add your question first!</a>';
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

if (isset($_POST['save_select'])) {
    $isValidationOK = true;
    $answer = $_POST['quiz_answer'];
    $getId = $_GET['Question_ID'];

    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {

            if ($isValidationOK = true && isset($_POST['correct'])) {
                $insertAnswerIntoBase = "INSERT INTO `answer`(`id`, `content`, `isCorrect`, `questionId`) VALUES ('', '$answer', 1, '$getId')";
                if ($connect->exec($insertAnswerIntoBase)) {
                    $_SESSION['answer_add_ended'] = true;
                    header('Location: add_answer.php?Question_ID=' . $getId);
                } else {
                    throw new Exception($connect->error);
                }
            }
                if ($isValidationOK = true && !isset($_POST['correct'])) {
                    $insertAnswerIntoBase = "INSERT INTO `answer`(`id`, `content`, `isCorrect`, `questionId`) VALUES ('', '$answer', 0, '$getId')";
                    if ($connect->exec($insertAnswerIntoBase)) {
                        $_SESSION['answer_add_ended'] = true;
                        header('Location: add_answer.php?Question_ID=' . $getId);
                    } else {
                        throw new Exception($connect->error);
                    }
                }

            }
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
    <title>Add Answer</title>
    <link rel="stylesheet" href="quiz_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<br>
Your Question: "<?php echo $questionName;?>"
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Add your Answers!</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="answer" placeholder="Answer"
                                   name="quiz_answer">
                            <label>
                                <input type="checkbox" class="form-control" name="correct"/>Correct
                            </label>
                        </div>
                        <br>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="add_question.php?Quiz_ID=<?php echo $quizId; ?>">Want add more question?</a>
                            <button type="submit" name="save_select" class="btn btn-primary">Add Answer</button>
                            <br>
                            <br>
                            <?php if($_SESSION['count']>=2){
                            echo '<a href="end_quiz.php">Done? Want end creating quiz?</a>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
