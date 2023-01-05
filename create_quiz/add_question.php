<?php
$_SESSION['count']= 0;
session_start();

//Status zalogowany jeśli  nie widnieje w sesji przenosi odrazu do pliku docelowego
if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
    exit();
}
if (!isset($_GET['Quiz_ID'])) {
    echo '<a style="color:red" href="create_quiz.php">Create your quiz first</a>';
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
    $question = $_POST['quiz_question'];
    $getId = $_GET['Quiz_ID'];

    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {
            if ($isValidationOK = true) {
                $insertQuestionIntoBase = "INSERT INTO `quiz_question`(`id`, `question`, `quizId`) VALUES ('', '$question','$getId')";
                if ($connect->exec($insertQuestionIntoBase)) {
                    $_SESSION['question_add_ended'] = true;
                    $findQuestionId="SELECT `id` FROM `quiz_question` WHERE `quizId`='$getId'";
                    $findQuestionIdResult = $connect->prepare($findQuestionId);
                    $findQuestionIdResult->execute();
                    while ($fetch = $findQuestionIdResult->fetch(PDO::FETCH_ASSOC)) {
                        $_SESSION['count']+=1;
                        $_SESSION['question_id'] = $fetch['id'];
                        $questionId=$_SESSION['question_id'];
                        header('Location: add_answer.php?Question_ID=' . $questionId);
                    }
                    $findQuestionName="SELECT `question` FROM `quiz_question` WHERE `quizId`='$getId'";
                    $findQuestionNameResult = $connect->prepare($findQuestionName);
                    $findQuestionNameResult->execute();
                    while ($fetch = $findQuestionNameResult->fetch(PDO::FETCH_ASSOC)) {
                        $_SESSION['question_name'] = $fetch['question'];
                        $questionName=$_SESSION['question_name'];
                    }
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
    <title>Add Question</title>
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
                <h2 class="card-title text-center">Add your Questions!</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="question" placeholder="Question" name="quiz_question">
                        </div>
                        <br>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="../index.php">Done? If u sure click it</a>
                            <button type="submit" name="save_select" class="btn btn-primary">Add Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
