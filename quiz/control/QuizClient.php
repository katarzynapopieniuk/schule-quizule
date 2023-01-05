<?php

class QuizClient {

    public function getQuizzesByCategory($category) {
        $databaseConnection = DatabaseClient::openConnection();
        $query = "SELECT * from quiz where category = '$category'";

        $result = mysqli_query($databaseConnection, $query);
        $quizzes = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $category = $row["category"];
                $isPublic = $row["isPublic"];
                $name = $row["name"];
                $id = $row["id"];
                $quiz = new Quiz($category, $isPublic, $name, $id, null);
                $quizzes[] = $quiz;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $quizzes;
    }

    public function getQuizzesById($quizId) {
        $databaseConnection = DatabaseClient::openConnection();
        $query = "SELECT * from quiz where id = '$quizId'";

        $result = mysqli_query($databaseConnection, $query);
        $quizzes = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $category = $row["category"];
                $isPublic = $row["isPublic"];
                $name = $row["name"];
                $id = $row["id"];
                $quiz = new Quiz($category, $isPublic, $name, $id, null);
                $questions = $this->getQuizQuestions($quiz, $databaseConnection);
                $quiz->setQuestions($questions);
                $quizzes[] = $quiz;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $quizzes;
    }

    public function getQuizQuestions($quiz, $databaseConnection) {
        $id = $quiz->getId();
        $quizQuestionQuery = "SELECT * from quiz_question where quizId = '$id'";
        $result = mysqli_query($databaseConnection, $quizQuestionQuery);
        $questions = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $question = $row["question"];
                $id = $row["id"];
                $answers = $this->getQuestionAnswers($id, $databaseConnection);
                $quizQuestion = new Question($question, $id, $answers);
                $questions[] = $quizQuestion;
            }
        }
        return $questions;
    }

    public function getQuestionAnswers($questionId, $databaseConnection) {
        $questionAnswersQuery = "SELECT * from answer where questionId = $questionId";
        $result = mysqli_query($databaseConnection, $questionAnswersQuery);
        $answers = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $content= $row["content"];
                $isCorrect = $row["isCorrect"];
                $id = $row["id"];
                $answer = new Answer($content, $isCorrect, $id, false);
                $answers[] = $answer;
            }
        }
        return $answers;
    }

    public function getQuizzesByTeacherId($teacherId) {
        $databaseConnection = DatabaseClient::openConnection();
        $query = "SELECT * from quiz where owner_id = '$teacherId'";

        $result = mysqli_query($databaseConnection, $query);
        $quizzes = array();

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $category = $row["category"];
                $isPublic = $row["isPublic"];
                $name = $row["name"];
                $id = $row["id"];
                $quiz = new Quiz($category, $isPublic, $name, $id, null);
                $quizzes[] = $quiz;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $quizzes;
    }

    public function getQuizzesSharedWithUserWithId($userId) {
        $getRoomWithUserIdQuery = "SELECT quizId from user_quiz where userId = $userId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getRoomWithUserIdQuery);

        $quizzesIds = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $quizId = $row["quizId"];
                $quizzesIds[] = $quizId;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);

        return $this->getQuizzesByIds($quizzesIds);
    }

    public function getQuizzesSharedWithRoomWithId($roomId) {
        $getRoomWithUserIdQuery = "SELECT quizId from room_quiz where roomId = $roomId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getRoomWithUserIdQuery);

        $quizzesIds = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $quizId = $row["quizId"];
                $quizzesIds[] = $quizId;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);

        return $this->getQuizzesByIds($quizzesIds);
    }

    public function isQuizSharedWithRoomWithId($quizId, $roomId) : bool {
        $getRoomWithUserIdQuery = "SELECT count(*) as total from room_quiz where roomId = $roomId and quizId=$quizId";
        $databaseConnection = DatabaseClient::openConnection();
        $result = mysqli_query($databaseConnection, $getRoomWithUserIdQuery);
        $data = $result->fetch_assoc();
        DatabaseClient::closeConnection($databaseConnection);
        return $data['total'] > 0;
    }

    public function shareQuizWithRoom($sharedQuizId, $sharedRoomId) {
        $databaseConnection = DatabaseClient::openConnection();
        $createAddUserToRoomQuery = "INSERT INTO room_quiz (quizId, roomId) VALUES ('$sharedQuizId', '$sharedRoomId')";
        mysqli_query($databaseConnection, $createAddUserToRoomQuery);
        DatabaseClient::closeConnection($databaseConnection);
    }

    public function unshareQuizWithRoom($sharedQuizId, $unsharedRoomId) {
        $databaseConnection = DatabaseClient::openConnection();
        $createAddUserToRoomQuery = "DELETE FROM room_quiz WHERE quizId='$sharedQuizId' and roomId='$unsharedRoomId'";
        mysqli_query($databaseConnection, $createAddUserToRoomQuery);
        DatabaseClient::closeConnection($databaseConnection);
    }

    /**
     * @param array $quizzesIds
     * @return array
     */
    private function getQuizzesByIds(array $quizzesIds): array {
        $quizzes = array();
        foreach ($quizzesIds as $quizId) {
            $quizzesWithGivenId = $this->getQuizzesById($quizId);
            foreach ($quizzesWithGivenId as $quizWithGivenId) {
                $quizzes[] = $quizWithGivenId;
            }
        }

        return $quizzes;
    }
}