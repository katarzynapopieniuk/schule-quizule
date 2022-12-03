<?php

use room\control\RoomClient;
use room\control\RoomCreator;
use room\control\RoomDisplay;
use room\display\RoomListDisplay;

session_start();

require_once("./quiz/entity/Quiz.php");
require_once("./quiz/entity/Answer.php");
require_once("./quiz/entity/Category.php");
require_once("./quiz/entity/Question.php");
require_once("./quiz/display/CategoryDisplay.php");
require_once("./quiz/display/QuizListDisplay.php");
require_once("./quiz/display/QuizDisplay.php");
require_once("./quiz/control/QuizClient.php");
require_once("./quiz/control/QuizResultCalculator.php");
require_once("./database/control/DatabaseClient.php");
require_once("./user/control/UserClient.php");
require_once("./user/display/UserDataDisplay.php");
require_once("./user/entity/AccountType.php");
require_once("./user/entity/User.php");
require_once("./room/control/RoomClient.php");
require_once("./room/control/RoomCreator.php");
require_once("./room/display/RoomCreatorDisplay.php");
require_once("./room/display/RoomDisplay.php");
require_once("./room/display/RoomListDisplay.php");
require_once("./room/entity/Room.php");
require_once("./room/entity/RoomCreatingException.php");
require_once("./room/entity/RoomWithNameAlreadyExistsException.php");

$quizClient = new QuizClient();
$userClient = new UserClient();
$roomClient = new RoomClient();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Schule Quizule</title>
    <meta charset="UTF-8">
    <meta name=""viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user/display/userData.css">
    <script src="./utilities.js" type="text/javascript"></script>
</head>
<body>

<!-- Navbar -->
<div class="top">
    
  <div class="top-left">
    <a href="#">
    <img src="LOGO.png" width = "20%" height = "20%"></a> 
        <a href="logging/logout.php"><?php if (isset($_SESSION['logged'])) {
                echo "Wyloguj";
            }
            ?></a>
        <a href="#"><?php if (isset($_SESSION['logged'])) {
                echo "<p>Welcome ".$_SESSION['email'].'!';
            }

            ?></a>
        <a href="#"> Temp</a>
  </div>

  <div class="top-mid">
      <?php
      if (isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
          echo '<div class="option" onclick="setSeeCurrentUserDataOptionPOST()">  Moje dane</div>';
      }
      if (isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_SESSION['accountType'])) {
          $accountType = $_SESSION['accountType'];
          if(AccountType::isTeacher($accountType)) {
              echo '<div class="option" onclick="setSeeCurrentUseRoomsOptionPOST()">  Moje pokoje</div>';
          }
      }

      ?>
  </div>

  <nav class="top-right">
    <div class="dropdown">
        <a href="#">Logowanie</a>
        <ul>
            <li><a href="logging/login.php">Zaloguj</a></li>
            <li><a href="logging/register.php">Zarejestruj</a></li>
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
        } else if(isset($_POST['submittedQuizId'])) {
            echo QuizResultCalculator::calculateQuizResult($_POST, $quizClient);
        } else if(isset($_POST['see_current_user_data']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
            UserDataDisplay::displayDataForUserWithId($_SESSION['Id'], $userClient);
        } else if(isset($_POST['see_current_user_rooms']) && isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_SESSION['accountType'])) {
            $accountType = $_SESSION['accountType'];
            if(AccountType::isTeacher($accountType)) {
                $rooms = $roomClient->getRoomsForTeacherId($_SESSION['Id']);
                RoomListDisplay::displayRoomList($rooms);
                RoomCreatorDisplay::displayCreator();
            }
        } else if(isset($_POST['createRoom']) && isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_POST['roomName'])) {
            RoomCreator::createRoom($_POST['roomName'], $_SESSION['Id'], $roomClient);
        } else if(isset($_POST['see_current_room']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
            RoomDisplay::displayRoomWithId($_POST['see_current_room'], $roomClient, $userClient);
        }
    ?>

</div>

    <div class="footer">
      <h4>Footer</h4>
    </div>