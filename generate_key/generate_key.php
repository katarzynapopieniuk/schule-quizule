<?php

session_start();

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require '../logging/PHPMailer-master/src/Exception.php';
require '../logging/PHPMailer-master/src/PHPMailer.php';
require '../logging/PHPMailer-master/src/SMTP.php';

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
if (isset($_POST['key_email'])) {
    //Zmienna innformująca o udanej walidacji
    $isValidationOK = true;

    $email = $_POST['key_email'];
    if (strlen($email) < 1) {
        $isValidationOK = false;
        $_SESSION['e_key_mail'] = "You have to type your email";
    }


//załączenie PHPMailera
    $mail_key = new PHPMailer(true);

    //Połączenie z bazą danych
    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        try {
            //Default templatka do działania PHPMailera (w tym momencie działa tylko na serwerze g-mail więc maile muszą być goglowskie)
            $mail_key->SMTPDebug = 0;
            $mail_key->isSMTP();
            $mail_key->Host = 'smtp.gmail.com';
            $mail_key->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail_key->Port = 465;
            $mail_key->SMTPAuth = true;
            $mail_key->Username = 'schule.quizule@gmail.com';
            $mail_key->Password = 'spnrrdvasopriody';
            $mail_key->CharSet = 'UTF-8';
            $mail_key->setFrom('no-reply@domena.pl', 'Schule Quizule');
            $mail_key->addAddress($email);
            $mail_key->addReplyTo('biuro@domena.pl', 'Biuro');
            $mail_key->isHTML(true);
            $key = uniqid();
            $mail_key->Subject = 'Account key';
            $mail_key->Body = '<p>Your secret accout key is: <b style="font-size: 30px;">' . $key . '</b></p>';
            $mail_key->send();

            //przypisanie połączenia do zmiennej
            $connect = DatabaseClient::createPDO();
            if (!$connect) {
                die("Fatal Error: Connection Failed!");
            } else { // sprawdzenie czy w bazie istnieje użytkownik o podanym e-mailu
                if ($isValidationOK == true) { //dodanie do bazy common usera czyli tego który nie zaznacza opcji 'Teacher' i nie wpisuje tajnego klucza
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertKey = "INSERT INTO `secretkey`(`id`, `secret`) VALUES ('', '$key')";
                    if ($connect->exec($insertKey)) {
                        $_SESSION['send_key_ended'] = true; //zakończenie rejestracji
                        $_SESSION['generate']="Wygenerowano klucz";
                        header('Location: generate_key.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
            }
            $connect=null;
            $isEmailExistResult=null;
        } catch (Exception $e) {
            "<br/> Message could not be sent. Mailer Error: {$mail_key->ErrorInfo}";
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
    <title>Generate</title>
    <link rel="stylesheet" href="../logging/login_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <h2 class="card-title text-center">Generate Account Key</h2>
                <div class="card-body py-md-4">
                    <form _lpchecked="1" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="key_email">
                        </div>
                        <?php
                        if (isset($_SESSION['e_key_mail'])) {
                            echo '<div class="error">' . $_SESSION['e_key_mail'] . '</div>';
                            unset($_SESSION['e_key_mail']);
                            unset($_SESSION['send_key_ended']);
                        }
                        if (isset($_SESSION['send_key_ended'])) {
                            echo '<div class="error">' . $_SESSION['generate'] . '</div>';
                            unset($_SESSION['e_key_mail']);
                        }
                        ?>
                            <br>
                            <div class="d-flex flex-row align-items-center justify-content-between">
                                <button type="submit" name="save_radio" class="btn btn-primary">Wygeneruj kod</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>


