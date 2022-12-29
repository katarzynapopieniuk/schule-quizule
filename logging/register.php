<?php

session_start();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';


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

//Warunki udanej rejestracji
if (isset($_POST['reg_email'])) {
    //Zmienna innformująca o udanej walidacji
    $isValidationOK = true;

    $email = $_POST['reg_email'];

    if (strlen($email) < 5 || (strlen($email) > 45)) {
        $isValidationOK = false;
        $_SESSION['e_email'] = "Email has to have between 5 and 45 chars!";
    }

    $password = $_POST['reg_password'];
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);
    $repeat_password = $_POST['reg_repeat'];

    if ((strlen($password) < 8) || (strlen($password) > 45) || !$uppercase || !$lowercase || !$number || !$specialChars) {
        $isValidationOK = false;
        $_SESSION['e_password'] = "Password has to be between 8 and 45 chars, has to have 1 number, 1 upper and lower case letter and 1 special char!";
    }

    if ($password != $repeat_password) {
        $isValidationOK = false;
        $_SESSION['e_password'] = "Passwords have to be the same!";
    }

    //zahashowanie hasła
    $user_password_hash = password_hash($password, PASSWORD_DEFAULT);


    $accountKey = $_POST['reg_accountKey'];


    if (!isset($_POST['regulamin'])) {
        $isValidationOK = false;
        $_SESSION['e_regulamin'] = "Accept the regulamin, please!";
    }
    if (isset($_POST['accountType']) && (strlen($accountKey) < 1)) {
        $isValidationOK = false;
        $_SESSION['e_accountKey'] = "Insert your Teacher Secret Key";
    }

    $name = $_POST['reg_name'];
    $surname = $_POST['reg_surname'];

//załączenie PHPMailera
    $mail = new PHPMailer(true);

    //Połączenie z bazą danych
    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        try {
            //Default templatka do działania PHPMailera (w tym momencie działa tylko na serwerze g-mail więc maile muszą być goglowskie)
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = 'polskiorzel19@gmail.com';
            $mail->Password = 'ewbitscqjmrqarjg';
            $mail->CharSet = 'UTF-8';
            $mail->setFrom('no-reply@domena.pl', 'Schule Quizule');
            $mail->addAddress($email);
            $mail->addReplyTo('biuro@domena.pl', 'Biuro');
            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            $mail->send();

            //przypisanie połączenia do zmiennej
            $connect = DatabaseClient::createPDO();
            if (!$connect) {
                die("Fatal Error: Connection Failed!");
            } else { // sprawdzenie czy w bazie istnieje użytkownik o podanym e-mailu
                $isEmailExist = "SELECT id FROM user WHERE email='$email'";
                $isEmailExistResult = $connect->query($isEmailExist);
                if (!$isEmailExistResult) throw new Exception($connect->error);

                $how_many_users = $isEmailExistResult->rowCount();
                if ($how_many_users > 0) {
                    $isValidationOK = false;
                    $_SESSION['e_email'] = "An account with this email already exists!";
                }
                if (isset($_POST['accountType'])) { //sprawdzenie czy wpisywany klucz nauczycielski jest zgodny z jednym z kluczy z tabeli kluczy
                    $findSecretKey = "SELECT secret FROM secretkey WHERE secret='$accountKey'";
                    $findSecretKeyResult = $connect->query($findSecretKey);
                    if (!$findSecretKeyResult) throw new Exception($connect->error);
                    $is_equal = $findSecretKeyResult->rowCount();
                    if ($is_equal <= 0) {
                        $isValidationOK = false;
                        $_SESSION['e_accountKey'] = "Your Teacher Key is wrong. Please try again!";
                    }

                }
                if (isset($_POST['accountType'])) { //sprawdzenie czy dany klucz został już użyty (zapobiega to użyciu 1 klucza kilka razy)
                    $isKeyUsed = "SELECT id FROM user WHERE accountKey='$accountKey'";
                    $isKeyUsedResult = $connect->query($isKeyUsed);
                    if ($isKeyUsedResult) throw new Exception($connect->error);

                    $how_many_keys = $isKeyUsedResult->rowCount();
                    if ($how_many_keys > 0) {
                        $isValidationOK = false;
                        $_SESSION['e_accountKey'] = "This key has already been used. Please contact support or try entering the key again!";
                    }
                }
                if ($isValidationOK == true && (!isset($_POST['accountType']))) { //dodanie do bazy common usera czyli tego który nie zaznacza opcji 'Teacher' i nie wpisuje tajnego klucza
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertUserIntoBase = "INSERT INTO `user`(`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`) VALUES ('', '$email','$user_password_hash','$name','$surname','user','$accountKey','$verification_code', 'false')";
                    if ($connect->exec($insertUserIntoBase)) {
                        $_SESSION['register_ended'] = true; //zakończenie rejestracji
                        header('Location: email.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
                if ($isValidationOK == true && (isset($_POST['accountType']))) { //dodanie do bazy nauczyciela czyli zaznaczającego opcję 'Teacher' i wpisującego tajny klucz
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertTeacherIntoBase = "INSERT INTO `user`(`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`) VALUES ('', '$email','$user_password_hash','$name','$surname','teacher','$accountKey','$verification_code','false')";
                    if ($connect->exec($insertTeacherIntoBase)) {
                        $_SESSION['register_ended'] = true;
                        header('Location: email.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
            }
            $connect=null;
            $isEmailExistResult=null;
        } catch (Exception $e) {
            "<br/> Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Register to Schule Quizule</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" placeholder="Name" name="reg_name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="surname" placeholder="Surname"
                                   name="reg_surname">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="reg_email">
                        </div>
                        <?php
                        if (isset($_SESSION['e_email'])) {
                            echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                            unset($_SESSION['e_email']);
                        }
                        ?>

                        <div class="form-group">
                            <input type="password" class="form-control" id="password" placeholder="Password"
                                   name="reg_password" > <img title="Min. 1 mała i 1 duża litera, 1 cyfra i 1 znak specjalny i w sumie 8 znaków"src="question.png" width="20px" height="20px" >
                        <?php
                        if (isset($_SESSION['e_password'])) {
                            echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
                            unset($_SESSION['e_password']);
                        }
                        ?>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirm-password"
                                   placeholder="Confirm password" name="reg_repeat">
                        </div>
                        <div class="form-group">

                            <input type="checkbox" id="accountType" class="form-control" name="accountType"/>Teacher
                            <label for="accountType" id="hide">
                                <div class="form-group" id="accountKey">
                                    <input type="text" class="form-control" placeholder="Account Key"
                                           name="reg_accountKey"><br>
                                </div>
                            </label>
                        </div>
                        <?php
                        if (isset($_SESSION['e_accountKey'])) {
                            echo '<div class="error">' . $_SESSION['e_accountKey'] . '</div>';
                            unset($_SESSION['e_accountKey']);
                        }
                        ?>
                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="form-control" name="regulamin"/>I'm accept regulamin of
                                this website
                            </label>
                        </div>
                        <?php
                        if (isset($_SESSION['e_regulamin'])) {
                            echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div>';
                            unset($_SESSION['e_regulamin']);
                        }
                        ?>
                        <br>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <a href="login.php">Login</a>
                            <button type="submit" name="save_radio" class="btn btn-primary">Create Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

