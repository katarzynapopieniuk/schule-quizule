<?php
session_start();

//Status zalogowany jeśli widnieje w sesji przenosi odrazu do pliku docelowego
if ((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
    header('Location: ../index.php');
    exit();
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $verificationCode = $_POST['verification_code'];
    $isValidationOk = true; //Zmienna innformująca o udanej walidacji

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = new PDO('mysql:host=localhost; dbname=schule_quizule', "root", "");
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else { //jeśli poprawnie wpiszemy nasz kod weryfikacyjny który przyjdzie do nas w mailu następuje modyfikacja pola isVerficate na true
            if ($isValidationOk == true) {
                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $updateEmailVerification = "UPDATE `user` SET `isVerificate`='true' WHERE `verification_code` = '" . $verificationCode . "'";
                $updateEmailVerificationResult = $connect->query($updateEmailVerification);
                if (!$updateEmailVerificationResult) throw new Exception($connect->error);
                $how_many_verified = $updateEmailVerificationResult->rowCount();
                if ($how_many_verified == 0) {
                    $isValidationOk = false;
                    die("Verification code failed.");
                } else {
                    $_SESSION['verificate'] = true;
                    header('Location: ../index.php');
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
    <title>Verification Email</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <h2 class="card-title text-center">Verify Your E-mail</h2>
            <div class="card-body py-md-4">
                <form _lpchecked="1" action="email.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="verification_code"
                               placeholder="Enter verification code" required>
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <input type="submit" name="email" value="Verify Email">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
