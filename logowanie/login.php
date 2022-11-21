<?php

session_start();
if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
{
    header('Location: index.php');
    exit();
}
if(isset($_POST['login'])) {
//Udana walidacja
    $is_OK = true;
    $login = $_POST['login'];
    $password = $_POST['log_password'];
//    $isVerificate=$_GET['log_verificate'];
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = new PDO('mysql:host=localhost; dbname=schule_quizule', "root", "");
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        } else {


            $sql1 = $sql = "SELECT * FROM user WHERE email=? ";
            $result = $connect->prepare($sql1);
            if (isset($login)) {
                $row = $result->execute(array($login));
                $how_many_users = $result->rowCount();
                $fetch = $result->fetch();
                if ($how_many_users > 0) {
                if(password_verify($password, $fetch['password'])){
//                    if($fetch->isVerificate == "true") {
                       $_SESSION['logged'] = true;
                       $_SESSION['Id'] = $fetch['Id'];
                        $_SESSION['email'] = $fetch['email'];
                        unset($_SESSION['error']);
                       $result->closeCursor();
                        header('Location: index.php');

//else {
//                        $is_OK=false;
//                        $_SESSION['error'] = '<span style="color:red">Your email is not verified</span>';
//                        header('Location: login.php');
//                    }
                    } else {
                        $is_OK=false;
                        $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect</span>';
                        header('Location: login.php');
                    }

                } else {
                    $is_OK=false;
                    $_SESSION['error'] = '<span style="color:red">Your login or password is incorrect</span>';
                    header('Location: login.php');
                }
            }
        }

    } catch (PDOException $exception) {
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
    <title>Logowanie</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=PT+Sans');
        body{
            background: #fff;
            font-family: 'PT Sans', sans-serif;
        }
        h2{
            padding-top: 1.5rem;
        }
        a{
            color: #333;
        }
        a:hover{
            color: #da5767;
            text-decoration: none;
        }
        .card{
            border: 0.40rem solid #f8f9fa;
            top: 10%;
        }
        .form-control{
            background-color: #f8f9fa;
            padding: 25px 15px;
            margin-bottom: 1.3rem;
        }

        .form-control:focus {

            color: #000000;
            background-color: #ffffff;
            border: 3px solid #da5767;
            outline: 0;
            box-shadow: none;

        }

        .btn{
            padding: 0.6rem 1.2rem;
            background: #da5767;
            border: 2px solid #da5767;
        }
        .btn-primary:hover {


            background-color: #df8c96;
            border-color: #df8c96;
            transition: .3s;

        }
    </style>
</head>
<body>
<a href="index.php">
    <img src="LOGO.png" width = "20%" height = "20%"></a>
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
                        <input type="password" class="form-control" id="password" placeholder="Password" name="log_password">
                    </div>
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="register.php">Create Account</a>
                        <button class="btn btn-primary">Login</button>
                    </div>
                </form>
                <?php
                if(isset($_SESSION['error'])) echo $_SESSION['error'];
                ?>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>

