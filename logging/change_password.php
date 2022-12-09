<?php
session_start();
//Status zalogowany jeśli widnieje w sesji przenosi odrazu do pliku docelowego
if ((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
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
if (!isset($_GET['code'])) {
    echo '<a style="color:red" href="forgot_password.php">Enter your email first</a>';
}
if (isset($_POST['change_password'])) {
    //Zmienna innformująca o udanej walidacji
    $isValidationOK = true;

    $password = $_POST['change_password'];
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $repeat_password = $_POST['change_repeat'];
    $passwordCode = $_GET['code'];

    if ((strlen($password) < 8) || (strlen($password) > 45) || !$uppercase || !$lowercase || !$number || !$specialChars) {
        $isValidationOK = false;
        $_SESSION['e_change_password'] = "Password has to be between 8 and 45 chars, has to have 1 number, 1 upper and lower case letter and 1 special char!";
    }

    if ($password != $repeat_password) {
        $isValidationOK = false;
        $_SESSION['e_change_repeat'] = "Passwords have to be the same!";
    }


//zahashowanie hasła
    $user_new_password_hash = password_hash($password, PASSWORD_DEFAULT);

    require_once "../database/control/DatabaseClient.php";


    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        //przypisanie połączenia do zmiennej
        $connect = DatabaseClient::createPDO();

        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {
            $timeOnThisPage2= time();
            if ($timeOnThisPage2 > $_SESSION['time_expire2']) {
                session_destroy();
                header('Location: time_out.php');
            } else {
                if ($isValidationOK == true) {
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $updateUserPassword = "UPDATE `user` SET `password`='$user_new_password_hash' WHERE `passwordCode` = '$passwordCode'";//zmienienie hasła i jego zahashowanie
                    if ($isValidationOK = false) {
                        $_SESSION['e_new_password'] = '<span style="color:red">Your login or password is incorrect</span>'; //jeśli hasło nie zgadza się
                        header('Location: change_password.php');
                    }
                    if ($connect->exec($updateUserPassword)) {
                        $_SESSION['changing_ended'] = true; //zakończenie zmiany hasła
                        header('Location: password_changed.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
            }
        }
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
    <title>Registration</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Change your password</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="password" class="form-control" id="new-password" placeholder="New password"
                                   name="change_password">
                        </div>
                        <?php
                        if (isset($_SESSION['e_change_password'])) {
                            echo '<div class="error">' . $_SESSION['e_change_password'] . '</div>';
                            unset($_SESSION['e_change_password']);
                        }
                        ?>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirm-new-password"
                                   placeholder="Confirm your new password" name="change_repeat">
                        </div>
                        <?php
                        if (isset($_SESSION['e_change_repeat'])) {
                            echo '<div class="error">' . $_SESSION['e_change_repeat'] . '</div>';
                            unset($_SESSION['e_change_repeat']);
                        }
                        ?>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <button type="submit" name="save_radio" class="btn btn-primary">Change password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
