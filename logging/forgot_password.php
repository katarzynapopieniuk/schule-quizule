<?php
unset($_SESSION['time_out']);
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
if (isset($_POST['send_email'])) {
    //Zmienna innformująca o udanej walidacji
    $isValidationOK = true;


        $email = $_POST['send_email'];

    if (strlen($email) < 5 || (strlen($email) > 45)) {
        $isValidationOK = false;
        $_SESSION['e_send_email'] = "Email has to have between 5 and 45 chars!";
    }

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
            $passwordCode = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Password reset verification';
            $mail->Body = '<p>Your password reset code is: <b style="font-size: 30px;">' . $passwordCode . '</b></p>';
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
                if ($how_many_users <= 0) {
                    $isValidationOK = false;
                    $_SESSION['e_send_email'] = "An account with this email doesn't exists!";
                }
                if ($isValidationOK == true) { //przypisuje/updateuje kod weryfikacyjny zmiany hasła w bazie i ustawia weryfikację na fałsz w celu kilkukrotnej możliwości zmiany hasła
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $updatePasswordCode = "UPDATE `user` SET `passwordCode`='$passwordCode', `passwordVerificate`=0 WHERE `email` = '$email'";
                    if ($connect->exec($updatePasswordCode)) {
                        //zakończenie
                        $_SESSION['time_start'] = time(); // przypisanie startu upływu czasu do zmiennej
                        $_SESSION['time_expire'] = $_SESSION['time_start'] + (2 * 60);// przypisanie maxymalnego upływu czasu do 2 minut;
                        $_SESSION['send_ended'] = true;
                        header('Location: password_code.php');
                    } else {
                        $_SESSION['send_ended'] = false;
                        throw new PDOException($connect->error);
                    }
                }
            }
            $connect = null;
            $isEmailExist = null;
            $insertPasswordCode = null;
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
    <!--    <script src="https://www.google.com/recaptcha/enterprise.js?render=6Lf6HHMgAAAAAAwZVMI5CgLMCALN7ZWh4ra3ad_V"></script>-->
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Forgot Password</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="send_email">
                        </div>
                        <?php
                        if (isset($_SESSION['e_send_email'])) {
                            echo '<div class="error">' . $_SESSION['e_send_email'] . '</div>';
                            unset($_SESSION['e_send_email']);
                        }
                        ?>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <button type="submit" name="save_radio" class="btn btn-primary">Send Password Reset Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
