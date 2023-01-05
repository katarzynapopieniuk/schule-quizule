<?php

use room\entity\Room;

class QuizSharer {

    public static function displayRoomsWithShareUnshareOptions($quizId, $teacherId, \room\control\RoomClient $roomClient, QuizClient $quizClient) {
        $teachersRooms = $roomClient->getRoomsForTeacherId($teacherId);
        foreach ($teachersRooms as $room) {
            if(self::isQuizAlreadySharedWithRoom($quizId, $room->getId(), $quizClient)) {
                self::displayRoomWithUnshareOption($quizId, $room);
            } else {
                self::displayRoomWithShareOption($quizId, $room);
            }
        }
    }

    public static function shareQuizWithRoom($sharedQuizId, $sharedRoomId, QuizClient $quizClient) {
        $quizClient->shareQuizWithRoom($sharedQuizId, $sharedRoomId);
    }

    public static function unshareQuizWithRoom($sharedQuizId, $unsharedRoomId, QuizClient $quizClient) {
        $quizClient->unshareQuizWithRoom($sharedQuizId, $unsharedRoomId);
    }

    private static function isQuizAlreadySharedWithRoom($quizId, $roomId, QuizClient $quizClient): bool {
        return $quizClient->isQuizSharedWithRoomWithId($quizId, $roomId);
    }

    private static function displayRoomWithUnshareOption($quizId, $room) {
        echo $room->getName();
        $roomId = $room->getId();
        ?>
        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="sharedQuizId" value="<?php print "$quizId" ?>"/>
            <input type="hidden" name="shareQuiz" value=""/>
            <input type="hidden" name="unsharedRoomId" value="<?php print "$roomId" ?>"/>
            <input type="submit" name="unshareQuizWithRoomId" value="anuluj udostępnienie">
        </form>
        <?php
    }

    private static function displayRoomWithShareOption($quizId, Room $room) {
        echo $room->getName();
        $roomId = $room->getId();
        ?>
        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="sharedQuizId" value="<?php print "$quizId" ?>"/>
            <input type="hidden" name="shareQuiz" value=""/>
            <input type="hidden" name="sharedRoomId" value="<?php print "$roomId" ?>"/>
            <input type="submit" name="shareQuizWithRoomId" value="udostępnij">
        </form>
        <?php
    }
}