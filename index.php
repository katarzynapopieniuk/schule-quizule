<?php

require_once("./quiz/entity/Quiz.php");
require_once("./quiz/entity/Answer.php");
require_once("./quiz/entity/Category.php");
require_once("./quiz/entity/Question.php");
require_once("./quiz/display/CategoryDisplay.php");
require_once("./quiz/display/QuizListDisplay.php");
require_once("./quiz/display/QuizDisplay.php");
require_once("./quiz/control/QuizClient.php");
require_once("./database/control/DatabaseClient.php");

$quizClient = new QuizClient();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Schule Quizule</title>
    <meta charset="UTF-8">
    <meta name=""viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="./utilities.js" type="text/javascript"></script>
</head>
<body>

<!-- Navbar -->
<div class="top">
    
  <div class="top-left">
    <a href="Quizule.html"> 
    <img src="LOGO.png" width = "20%" height = "20%"></a> 
        <a href="#">Temp</a>
        <a href="#">Temp</a>
        <a href="#">Temp</a>    
  </div>

  <nav class="top-right">
    <div class="dropdown">
        <a href="#">Logowanie</a>
        <ul>
            <li><a href="#">Zaloguj</a></li>
            <li><a href="#">Zarejestruj</a></li>
        </ul>
    </div>
  </nav>
</div>

<!-- Sidebar -->
<div class="left-column">
    <nav class="topics-menu">
        <?php CategoryDisplay::display(Category::getCategories()); ?>
    </nav>
</div>

<div class="main" style="margin-left:250px">
    <?php
        if(isset($_POST['current_category'])) {
            $category = $_POST['current_category'];
            echo "Wybrano karegorie: " . $category . "</br>";
            $quizzes = $quizClient->getQuizzesByCategory($category);

            if(is_array($quizzes) || is_object($quizzes)) {
                QuizListDisplay::display($quizzes);
            }
        } elseif (isset($_POST['current_quiz'])) {
            $quizId = $_POST['current_quiz'];
            $quizzes = $quizClient->getQuizzesById($quizId);

            if(is_array($quizzes) || is_object($quizzes)) {
                QuizDisplay::display($quizzes[0]);
            }
        }
    ?>

</div>

    <div class="footer">
      <h4>Footer</h4>
    </div>