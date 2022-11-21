<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Schule Quizule</title>
    <meta charset="UTF-8">
    <meta name=""viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<div class="top">
    
  <div class="top-left">
    <a href="#">
    <img src="LOGO.png" width = "20%" height = "20%"></a> 
        <a href="wyloguj.php"><?php if (isset($_SESSION['logged']))
            {
                echo "Wyloguj";
            }
            ?></a>
        <a href="#"><?php if (isset($_SESSION['logged']))
            {
                echo "<p>Welcome ".$_SESSION['email'].'!';
            }

            ?></a>
        <a href="#"> Temp</a>
  </div>

  <nav class="top-right">
    <div class="dropdown">
        <a href="#">Logowanie</a>
        <ul>
            <li><a href="login.php">Zaloguj</a></li>
            <li><a href="register.php">Zarejestruj</a></li>
        </ul>
    </div>
  </nav>
</div>

<!-- Sidebar -->
<div class="left-column">
    <nav class="topics-menu">
        <a href="#">Polski</a>
        <a href="#">Matematyka</a>
        <a href="#">Przyroda</a>
        <a href="#">Angielski</a>

    </nav>
</div>

<div class="main" style="margin-left:250px">
    

</div>

    <div class="footer">
      <h4>Footer</h4>
    </div>