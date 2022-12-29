<?php
$_SESSION['attemps']= 0;

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

if (isset($_POST['login'])) {
//Zmienna innformująca o udanej walidacji
    $isValidationOK = true;
    $login = $_POST['login'];
    $password = $_POST['log_password'];

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
                    if($fetch['isVerificate']) {
                        if (password_verify($password, $fetch['password'])) { //odhashowywanie hasła i sprawdzenie czy zgadza się z hasłem zhashownym
                            $_SESSION['logged'] = true;
                            $_SESSION['Id'] = $fetch['id'];
                            $_SESSION['email'] = $fetch['email'];
                            $_SESSION['accountType'] = $fetch['accountType'];
                            unset($_SESSION['error']);
                            $findEqualMailResult->closeCursor();
                            header('Location: ../index.php'); //koniec logowania przenosi do strony głównej
                        } else {
                            $_SESSION['attemps'] += 1;
                            $isValidationOK = false;
                            $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect. Your try: </span>' . $_SESSION['attemps'] . '<br> <span style="color:red"> After 3 tries you will have to wait minute</span>'; //jeśli hasło nie zgadza się
                            header('Location: login.php');
                            if ($_SESSION['attemps'] >= 3) {
                                $_SESSION['overload'] = true;
                                header("Location:attemps_overload.php");
                            }
                        }
                    }else{
                        $_SESSION['attemps'] += 1;
                        $isValidationOK = false;
                        $_SESSION['error'] = '<span style="color:red">Your email is not verified. <a href="email.php">Go verify it </a> Your try: </span>' . $_SESSION['attemps'] . '<br> <span style="color:red"> After 3 tries you will have to wait minute</span>'; //jeśli hasło nie zgadza się
                        header('Location: login.php');
                        if ($_SESSION['attemps'] >= 3) {
                            $_SESSION['overload'] = true;
                            header("Location:attemps_overload.php");
                        }
                    }

                } else
                {$_SESSION['attemps'] += 1;
                    $isValidationOK = false;
                    $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect. Your try: </span>' . $_SESSION['attemps'] . '<br> <span style="color:red"> After 3 tries you will have to wait minute</span>'; //jeśli hasło nie zgadza się
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
                        <a href="forgot_password.php">Forget password?</a>
                        <button class="btn btn-primary">Login</button>
                    </div>
                    <br>
                    <p>Not a member yet? <a href="register.php"> Sign up now!</a></p>
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

