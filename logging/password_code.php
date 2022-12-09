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


if (isset($_POST['password_code'])) {
    $passwordCode = $_POST['password_code'];
    $isValidationOk = true; //Zmienna innformująca o udanej walidacji


    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {
            $tiimeOnThisPage1 = time(); //przypisanie startu odliczania czasu do zmiennej
            if ($tiimeOnThisPage1 > $_SESSION['time_expire']) { //zakończenie sesji jeśli czas między podstronami przekroczy limit
                session_destroy();
                header('Location: time_out.php');
            } else {
                if ($isValidationOk == true) {
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $updatePasswordVerification = "UPDATE `user` SET `passwordVerificate`=1 WHERE `passwordCode` = '$passwordCode'";//jeśli poprawnie wpiszemy nasz kod weryfikacyjny który przyjdzie do nas w mailu następuje modyfikacja pola passwordVerificate na true
                    $updatePasswordVerificationResult = $connect->query($updatePasswordVerification);
                    if (!$updatePasswordVerificationResult) throw new Exception($connect->error);
                    $how_many_verified = $updatePasswordVerificationResult->rowCount();
                    if ($how_many_verified == 0) {
                        $isValidationOk = false;
                        die("Verification code failed.");
                    } else {
                        $_SESSION['time_start2'] = time(); //przypisanie startu upływu czasu do zmiennej
                        // Ending a session in 30 minutes from the starting time.
                        $_SESSION['time_expire2'] = $_SESSION['time_start2'] + (2 * 60);// przypisanie maxymalnego upływu czasu do 2 minut;
                        $_SESSION['time_out'] = false;
                        $_SESSION['verificate'] = true;
                        header('Location: change_password.php?code=' . $passwordCode);
                    }
                }
            }
        }
        $connect = null;
        $updatePasswordVerificationResult = null;
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
    <title>Verification Password</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <h2 class="card-title text-center">Verify Your Password Code</h2>
            <div class="card-body py-md-4">
                <form _lpchecked="1" action="password_code.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="password_code"
                               placeholder="Enter verification code" required>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <input type="submit" name="send_change_password" value="Verify Password">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
