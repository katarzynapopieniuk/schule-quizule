<?php
session_start();

//Status zalogowany jeśli  nie widnieje w sesji przenosi odrazu do pliku docelowego
if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
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
//Warunki udanej rejestracji
if (isset($_POST['save_select'])) {
//Zmienna innformująca o udanej walidacji
    $isValidationOK = true;

    $email=$_SESSION['email'];
    $loggedId = $_SESSION['Id'];
    $quizName = $_POST['quiz_name'];
    $quizCategory=$_POST['quiz_category'];

    if(!$quizName){
        $isValidationOK=false;
        $_SESSION['e_quiz_name']="You have to put your quiz name";
    }

    if(!$quizCategory){
        $isValidationOK=false;
        $_SESSION['e_quiz_category']="You have to choose your quiz category";
    }

    if(!isset($_POST['public']) && !isset($_POST['notpublic'])){
        $isValidationOK=false;
        $_SESSION['e_quiz_visibility']="You have to choose your quiz visibility";
    }

//Połączenie z bazą danych
    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        //przypisanie połączenia do zmiennej
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {
            if ($isValidationOK == true) {
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if(isset($_POST['public'])) {
                        $insertQuizIntoBase = "INSERT INTO `quiz`(`id`, `category`, `name`, `isPublic`, `owner`, `owner_id`) VALUES ('','$quizCategory','$quizName', 1,'$email','$loggedId')";

                        if ($connect->exec($insertQuizIntoBase)) {
                            $_SESSION['quiz_add_ended'] = true;
                            $findQuizId = "SELECT `id` FROM `quiz` WHERE `owner`='$email'";
                            $findQuizIdResult = $connect->prepare($findQuizId);
                            $findQuizIdResult->execute();
                            while ($fetch = $findQuizIdResult->fetch(PDO::FETCH_ASSOC)) {
                                $_SESSION['quiz_id'] = $fetch['id'];
                                $quizId = $_SESSION['quiz_id'];
                                header('Location: add_question.php?Quiz_ID=' . $quizId);
                            }
                        } else {
                            throw new Exception($connect->error);
                        }
                    }
                if(isset($_POST['notpublic'])) {
                    $insertQuizIntoBase = "INSERT INTO `quiz`(`id`, `category`, `name`, `isPublic`, `owner`, `owner_id`) VALUES ('','$quizCategory','$quizName', 0,'$email','$loggedId')";

                    if ($connect->exec($insertQuizIntoBase)) {
                        $_SESSION['quiz_add_ended'] = true;
                        $findQuizId = "SELECT `id` FROM `quiz` WHERE `owner`='$email'";
                        $findQuizIdResult = $connect->prepare($findQuizId);
                        $findQuizIdResult->execute();
                        while ($fetch = $findQuizIdResult->fetch(PDO::FETCH_ASSOC)) {
                            $_SESSION['quiz_id'] = $fetch['id'];
                            $quizId = $_SESSION['quiz_id'];
                            header('Location: add_question.php?Quiz_ID=' . $quizId);
                        }
                    } else {
                        throw new Exception($connect->error);
                    }
                }
            }
        }
        $connect=null;
    } catch (PDOException $exception) { //errory i informacje o błędach z połączeniem lub błędem w kodzie
        echo $exception->getMessage();
        echo '<span style="color:red;">Server error. Sorry for that try later!</span>';
        echo '<br/> Developer information: ' . $exception;
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
    <title>Create Quiz</title>
    <link rel="stylesheet" href="quiz_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Create your Quiz</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" placeholder="Name" name="quiz_name">
                        </div>
                        <?php
                        if (isset($_SESSION['e_quiz_name'])) {
                            echo '<div class="error">' . $_SESSION['e_quiz_name'] . '</div>';
                            unset($_SESSION['e_quiz_name']);
                        }
                        ?>
                        <br>
                        <div class="form-group">
                           <label for="">Category</label>
                            <select id="quiz_category" name="quiz_category" class="form-control">
                                <option value="">--Select Category--</option>
                                <option  value="polski">Polski</option>
                                <option  value="matma">Matematyka</option>
                                <option  value="przyroda">Przyroda</option>
                                <option  value="angielski">Angielski</option>
                            </select>
                        </div>
                        <?php
                        if (isset($_SESSION['e_quiz_category'])) {
                            echo '<div class="error">' . $_SESSION['e_quiz_category'] . '</div>';
                            unset($_SESSION['e_quiz_category']);
                        }
                        ?>
                        <br>
                        <div class="form-group">
                            <input type="radio" id="public" name="public" value="Public">
                            <label for="public">Public</label><br>
                            <input type="radio" id="notpublic" name="notpublic" value="Not Public">
                            <label for="notpublic">Not Public</label><br>
                        </div>
                        <?php
                        if (isset($_SESSION['e_quiz_visibility'])) {
                            echo '<div class="error">' . $_SESSION['e_quiz_visibility'] . '</div>';
                            unset($_SESSION['e_quiz_visibility']);
                        }
                        ?>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <button type="submit" name="save_select" class="btn btn-primary">Create Quiz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
