<?php
session_start();
if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
{
    header('Location: index.php');
    exit();
}
if (isset($_POST['email'])){
    $email=$_POST['email'];
    $verification_code = $_POST['verification_code'];
    $is_OK = true;

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connect = new PDO('mysql:host=localhost; dbname=schule_quizule', "root", "");
        if (!$connect) {
            die("Fatal Error: Connection Failed!");
        }
        else {
            if($is_OK==true){
                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql1="UPDATE `user` SET `isVerificate`='true' WHERE `verification_code` = '" . $verification_code . "'";
                $result = $connect->query($sql1);
                if (!$result) throw new Exception($connect->error);
                $how_many = $result->rowCount();
                if ($how_many == 0) {
                    $is_OK = false;
                    die("Verification code failed.");
                }
                else{
                    $_SESSION['verificate'] = true;
                    header('Location: index.php');
               }
            }
        }

    }catch (PDOException $exception) {
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
    <title>Verification Email</title>
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
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <h2 class="card-title text-center">Verify Your E-mail</h2>
            <div class="card-body py-md-4">
                <form _lpchecked="1" action="email.php" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control" name="verification_code" placeholder="Enter verification code" required>
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
