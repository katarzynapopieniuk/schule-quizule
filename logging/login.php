<?php

session_start();
//Status zalogowany jeśli widnieje w sesji przenosi odrazu do pliku docelowego
if ((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)) {
    header('Location: ../index.php');
    exit();
}
if (isset($_POST['login'])) {
//Zmienna innformująca o udanej walidacji
    $isValidationOK = true;
    $login = $_POST['login'];
    $password = $_POST['log_password'];
//    $isVerificate=$_GET['log_verificate'];
    require_once "../database/control/DatabaseClient.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = DatabaseClient::createPDO();
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else { //znalezienie w bazie pasującego do wpisanego e-maila i porównanie z przypisanym do tego maila hasła i id użytkownika jeśli się zgadzają wpuszcza do systemu jeśli nie stosowany komunikat


            $findEqualMail = "SELECT * FROM user WHERE email=? ";
            $findEqualMailResult = $connect->prepare($findEqualMail);
            if (isset($login)) {
                $row = $findEqualMailResult->execute(array($login));
                $how_many_mails = $findEqualMailResult->rowCount();
                $fetch = $findEqualMailResult->fetch();
                if ($how_many_mails > 0) {
                    if (password_verify($password, $fetch['password'])) { //odhashowywanie hasła i sprawdzenie czy zgadza się z hasłem zhashownym
//                    if($fetch->isVerificate == "true") {
                        $_SESSION['logged'] = true;
                        $_SESSION['Id'] = $fetch['id'];
                        $_SESSION['email'] = $fetch['email'];
                        $_SESSION['accountType'] = $fetch['accountType'];
                        unset($_SESSION['error']);
                        $findEqualMailResult->closeCursor();
                        header('Location: ../index.php'); //koniec logowania przenosi do strony głównej

//else {
//                        $is_OK=false;
//                        $_SESSION['error'] = '<span style="color:red">Your email is not verified</span>';
//                        header('Location: login.php');
//                    }
                    } else {
                        $isValidationOK = false;
                        $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect</span>'; //jeśli hasło nie zgadza się
                        header('Location: login.php');
                    }

                } else {
                    $isValidationOK = false;
                    $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect</span>'; //jeśli e-mail się nie zgadza
                    header('Location: login.php');
                }
            }
        }
        $connect = null;
        $findEqualMailResult = null;
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
    <title>Logowanie</title>
    <link rel="stylesheet" href="login_style.css">
</head>
<body>
<a href="../index.php">
    <img src="../LOGO.png" width="20%" height="20%"></a>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <h2 class="card-title text-center">Login to Schule Quizule</h2>
            <div class="card-body py-md-4">
                <form _lpchecked="1" action="login.php" method="post">
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" placeholder="Login(email)" name="login">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" placeholder="Password"
                               name="log_password">
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="register.php">Create Account</a>
                        <button class="btn btn-primary">Login</button>
                    </div>
                </form>
                <?php
                if (isset($_SESSION['error'])) echo $_SESSION['error'];
                ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

