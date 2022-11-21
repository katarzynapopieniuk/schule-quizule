<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
{
    header('Location: index.php');
    exit();
}

if(isset($_POST['reg_email'])){
    //Udana walidacja
    $is_OK=true;

    $email=$_POST['reg_email'];

    if(strlen($email)<5 || (strlen($email)>45)){
        $is_OK=false;
        $_SESSION['e_email']="Email has to have between 5 and 45 chars!";
    }

    $password=$_POST['reg_password'];
    $repeat_password=$_POST['reg_repeat'];

    if((strlen($password)<8) || (strlen($password)>45)) {
        $is_OK = false;
        $_SESSION['e_password'] = "Password has to have between 8 and 45 chars!";
    }

    if($password!=$repeat_password){
        $is_OK = false;
        $_SESSION['e_password'] = "Passwords have to be the same!";
    }

   $user_password_hash=password_hash($password, PASSWORD_DEFAULT);


    $accountKey=$_POST['reg_accountKey'];


    if(!isset($_POST['regulamin'])){
        $is_OK = false;
        $_SESSION['e_regulamin'] = "Accept the regulamin, please!";
    }
    if(isset($_POST['accountType']) && (strlen($accountKey)<1)){
        $is_OK = false;
        $_SESSION['e_accountKey'] = "Insert your Teacher Secret Key";
    }
//    $secret ="6Lf6HHMgAAAAAAwZVMI5CgLMCALN7ZWh4ra3ad_V";
//
//    $check=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
//
//    $answer=json_decode($check);

//        if($answer->success == false){
//           $is_OK = false;
//          $_SESSION['e_bot'] = "You have to verify that you aren't bot!";
//       }

    $name=$_POST['reg_name'];
    $surname=$_POST['reg_surname'];


    $mail= new PHPMailer(true);

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->SMTPAuth=true;
            $mail->Username = 'polskiorzel19@gmail.com';
            $mail->Password = 'ewbitscqjmrqarjg';
            $mail->CharSet='UTF-8';
            $mail->setFrom('no-reply@domena.pl', 'Schule Quizule');
            $mail->addAddress($email);
            $mail->addReplyTo('biuro@domena.pl', 'Biuro');
            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            $mail->send();

            $connect = new PDO('mysql:host=localhost; dbname=schule_quizule', "root", "");
            if (!$connect) {
                die("Fatal Error: Connection Failed!");
            } else {
                $sql1 = "SELECT id FROM user WHERE email='$email'";
                $result = $connect->query($sql1);
                if (!$result) throw new Exception($connect->error);

                $how_many = $result->rowCount();
                if ($how_many > 0) {
                    $is_OK = false;
                    $_SESSION['e_email'] = "An account with this email already exists!";
                }
                if (isset($_POST['accountType'])) {
                    $sql3 = "SELECT secret FROM secretkey WHERE secret='$accountKey'";
                    $result2 = $connect->query($sql3);
                    if (!$result2) throw new Exception($connect->error);
                    $is_equal = $result2->rowCount();
                    if ($is_equal <= 0) {
                        $is_OK = false;
                        $_SESSION['e_accountKey'] = "Your Teacher Key is wrong. Please try again!";
                    }

                }
                if (isset($_POST['accountType'])) {
                    $sql4 = "SELECT id FROM user WHERE accountKey='$accountKey'";
                    $result3 = $connect->query($sql4);
                    if (!$result3) throw new Exception($connect->error);

                    $how_many2 = $result3->rowCount();
                    if ($how_many2 > 0) {
                        $is_OK = false;
                        $_SESSION['e_accountKey'] = "This key has already been used. Please contact support or try entering the key again!";
                    }
                }
                if ($is_OK == true && (!isset($_POST['accountType']))) {
                    //dodaj do bazy
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql2 = "INSERT INTO `user`(`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`) VALUES ('', '$email','$user_password_hash','$name','$surname','user','$accountKey','$verification_code', 'false')";
                    if ($connect->exec($sql2)) {
                        $_SESSION['register_ended'] = true;
                        header('Location: email.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
                if ($is_OK == true && (isset($_POST['accountType']))) {
                    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql2 = "INSERT INTO `user`(`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`) VALUES ('', '$email','$user_password_hash','$name','$surname','teacher','$accountKey','$verification_code','false')";
                    if ($connect->exec($sql2)) {
                        $_SESSION['register_ended'] = true;
                        header('Location: email.php');
                    } else {
                        throw new Exception($connect->error);
                    }
                }
            }
        }
        catch (Exception $e){
            "<br/> Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    catch (PDOException $exception){
        echo $exception->getMessage();
        echo '<span style="color:red;">Server error. Sorry for that try later!</span>';
        echo '<br/> Developer information: '.$exception;
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
    <style>
        @import url('https://fonts.googleapis.com/css?family=PT+Sans');
        .error{
            color: red;
            margin-top: 10px;
            margin-bottom: 10px;
        }


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

        #accountKey {
            visibility: collapse;
        }


        #hide {
            visibility: hidden;
        }

        #accountType:checked ~ * #accountKey {
            visibility: visible;
        }

        #accountType:checked ~ #hide {
            visibility: visible;
        }



    </style>
</head>
<body>
<a href="index.php">
    <img src="LOGO.png" width = "20%" height = "20%"></a>
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
                            <input type="text" class="form-control" id="surname" placeholder="Surname" name="reg_surname">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="Email" name="reg_email">
                        </div>
                        <?php
                        if(isset($_SESSION['e_email'])){
                            echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                            unset($_SESSION['e_email']);
                        }
                        ?>

                        <div class="form-group">
                            <input type="password" class="form-control" id="password" placeholder="Password" name="reg_password">
                        </div>
                        <?php
                        if(isset($_SESSION['e_password'])){
                            echo '<div class="error">'.$_SESSION['e_password'].'</div>';
                            unset($_SESSION['e_password']);
                        }
                        ?>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirm-password" placeholder="Confirm password" name="reg_repeat">
                   </div>
                            <div class="form-group">

                                <input type="checkbox" id="accountType" class="form-control" name="accountType"/>Teacher
                           <label for="accountType" id="hide">
                                <div class="form-group" id="accountKey">
                                    <input type="text" class="form-control"  placeholder="Account Key" name="reg_accountKey"><br>
                                </div>
                           </label>
                            </div>
                            <?php
                            if(isset($_SESSION['e_accountKey'])){
                                echo '<div class="error">'.$_SESSION['e_accountKey'].'</div>';
                                unset($_SESSION['e_accountKey']);
                            }
                            ?>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" class="form-control" name="regulamin"/>I'm accept regulamin of this website
                                </label>
                            </div>
                            <?php
                            if(isset($_SESSION['e_regulamin'])){
                                echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                                unset($_SESSION['e_regulamin']);
                            }
                            ?>
                        <br>
<!--                        <div class="g-recaptcha" data-sitekey="Ld7NB0jAAAAAJi1sLXx0kY3puqYbCabU2uIxJFs"></div>-->
<!--                        --><?php
//                        if(isset($_SESSION['e_bot'])){
//                            echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
//                            unset($_SESSION['e_bot']);
//                        }
//                        ?>
<!--                        <br/>-->
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

