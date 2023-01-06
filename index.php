<?php

use room\control\RoomClient;
use room\control\RoomCreator;
use room\control\RoomDisplay;
use room\control\RoomManager;
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
require_once("./quiz/control/QuizSharer.php");
require_once("./database/control/DatabaseClient.php");
require_once("./user/control/UserClient.php");
require_once("./user/display/UserDataDisplay.php");
require_once("./user/entity/AccountType.php");
require_once("./user/entity/MissingUserException.php");
require_once("./user/entity/User.php");
require_once("./room/control/RoomClient.php");
require_once("./room/control/RoomCreator.php");
require_once("./room/control/RoomManager.php");
require_once("./room/display/RoomCreatorDisplay.php");
require_once("./room/display/RoomDisplay.php");
require_once("./room/display/RoomListDisplay.php");
require_once("./room/entity/AddingUserToRoomException.php");
require_once("./room/entity/Room.php");
require_once("./room/entity/RoomCreatingException.php");
require_once("./room/entity/RoomWithNameAlreadyExistsException.php");

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
unset($_SESSION['quiz_add_ended']);
unset($_SESSION['question_add_ended']);
unset($_SESSION['answer_add_ended']);
$quizClient = new QuizClient();
$userClient = new UserClient();
$roomClient = new RoomClient();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Schule Quizule</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="user/display/userData.css">
    <script src="./utilities.js" type="text/javascript"></script>
</head>
<body>

<!-- Navbar -->
    
<div class="col-1">
    <a href="#">
    <img src="LOGO.png" alt="logo" </a>

    <!-- Sidebar -->
    <nav class="topics-menu">
        <?php CategoryDisplay::display(Category::getCategories()); ?>
    </nav>

</div>

<div class="col-2">
    <header>
        <div class="top-left">
            <a href="logging/logout.php"><?php if (isset($_SESSION['logged'])) {
                    echo "Wyloguj";
                }
                ?></a>
            <a href="#"><?php if (isset($_SESSION['logged'])) {
                    echo "<p>Welcome ".$_SESSION['email'].'!';
                }

                ?></a>
            <a href="create_quiz/create_quiz.php"> <?php if (isset($_SESSION['logged']) && isset($_SESSION['accountType'])) {
                    $accountType = $_SESSION['accountType'];
                    if(AccountType::isTeacher($accountType)) {
                        echo "Stwórz Quiz";
                    }

                }
                ?>
            </a>
            <a href="#"> Temp</a>
        </div>

              <?php
              if (isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
                  echo '<div class="option" onclick="setSeeCurrentUserDataOptionPOST()">  Moje dane</div>';
              }
              if (isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_SESSION['accountType'])) {
                  $accountType = $_SESSION['accountType'];
                  if(AccountType::isTeacher($accountType) || AccountType::isUser($accountType)) {
                      echo '<div class="option" onclick="setSeeCurrentUseRoomsOptionPOST()">  Moje pokoje</div>';
                  }

                  echo '<div class="option" onclick="setSeeMyQuizzesOptionPOST()">  Moje quizy</div>';

              }

              ?>

            <nav class="dropdown">
                <a href="#">Logowanie</a>
                <ul>
                    <li><a href="logging/login.php">Zaloguj</a></li>
                    <li><a href="logging/register.php">Zarejestruj</a></li>
                </ul>
            </nav>
    </header>
</div>

    <main class="content" style="margin-left:250px">
        <article>Article</article>
        <?php
        if(isset($_POST['current_category'])) {
            $category = $_POST['current_category'];
            echo "Wybrano karegorie: " . $category . "</br>";
            $quizzes = $quizClient->getQuizzesByCategory($category);

            if(is_array($quizzes) || is_object($quizzes)) {
                QuizListDisplay::display($quizzes);
            }
        } else if(isset($_POST['current_quiz'])) {
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
            } else if(AccountType::isUser($accountType)) {
                $rooms = $roomClient->getRoomsForUserId($_SESSION['Id']);
                RoomListDisplay::displayRoomList($rooms);
            }
        } else if(isset($_POST['createRoom']) && isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_POST['roomName'])) {
            RoomCreator::createRoom($_POST['roomName'], $_SESSION['Id'], $roomClient);
        } else if(isset($_POST['see_current_room']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
            RoomDisplay::displayRoomWithId($_POST['see_current_room'], $roomClient, $userClient);
        } else if(isset($_POST['add_user_to_room']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
            RoomDisplay::displayAddingUserToRoomForm($_POST['add_user_to_room']);
        } else if(isset($_POST['add_user_with_email_to_room']) && isset($_SESSION['logged']) && isset($_SESSION['Id'])) {
            RoomManager::addUserToRoom($_POST['addedUserEmail'], $_POST['roomId'], $roomClient, $userClient);
        } else if(isset($_POST['removeUserFromRoom']) && isset($_POST['roomId']) && isset($_POST['userId'])) {
            RoomManager::removeUserFromRoom($_POST['userId'], $_POST['roomId'], $roomClient);
            RoomDisplay::displayRoomWithId($_POST['roomId'], $roomClient, $userClient);
        } else if(isset($_POST['see_my_quizzes']) && isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_SESSION['accountType'])) {
            $accountType = $_SESSION['accountType'];
            $loggedUserId = $_SESSION['Id'];
            if(AccountType::isTeacher($accountType)) {
                $quizzes = $quizClient->getQuizzesByTeacherId($loggedUserId);
                QuizListDisplay::displayQuizListAsOwner($quizzes);
            } elseif (AccountType::isUser($accountType)) {
                $quizzes = $quizClient->getQuizzesSharedWithUserWithId($loggedUserId);

                $rooms = $roomClient->getRoomsForUserId($loggedUserId);
                foreach ($rooms as $room) {
                    $quizzesSharedWithRoom = $quizClient->getQuizzesSharedWithRoomWithId($room->getId());
                    foreach ($quizzesSharedWithRoom as $quiz) {
                        $quizzes[] = $quiz;
                    }
                }

                QuizListDisplay::displayQuizListAsOwner($quizzes);
            }
        } else if(isset($_POST['current_quiz_for_owner']) && isset($_SESSION['logged']) && isset($_SESSION['Id']) && isset($_SESSION['accountType'])) {
            $accountType = $_SESSION['accountType'];
            $loggedUserId = $_SESSION['Id'];
            $quizId = $_POST['current_quiz_for_owner'];
            if(AccountType::isTeacher($accountType)) {
                QuizDisplay::displayOwnerQuizOptions($quizId, $roomClient, $userClient);
            }
        } else if(isset($_POST['shareQuiz']) && isset($_POST['sharedQuizId'])) {
            if(isset($_POST['shareQuizWithRoomId']) && isset($_POST['sharedRoomId'])) {
                QuizSharer::shareQuizWithRoom($_POST['sharedQuizId'], $_POST['sharedRoomId'], $quizClient);
            } else if(isset($_POST['unshareQuizWithRoomId']) && isset($_POST['unsharedRoomId'])) {
                QuizSharer::unshareQuizWithRoom($_POST['sharedQuizId'], $_POST['unsharedRoomId'], $quizClient);
            } else if (isset($_POST['share_quiz_with_user_with_email'])) {
                QuizSharer::shareQuizWithUserWithEmail($_POST['sharedQuizId'], $_POST['share_quiz_with_user_with_email'], $quizClient, $userClient);
            }
            QuizSharer::displayRoomsWithShareUnshareOptions($_POST['sharedQuizId'], $_SESSION['Id'], $roomClient, $quizClient);
            QuizSharer::displayShareWithUserOption($_POST['sharedQuizId'], $quizClient);
        }
        ?>
    </main>
    <footer>
        <h4>Footer</h4>
    </footer>


</body>






