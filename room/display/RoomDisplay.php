<?php

namespace room\control;

use room\entity\MissingRoomException;
use room\entity\Room;
use UserClient;
use UserDataDisplay;

class RoomDisplay {

    public static function display(Room $room, RoomClient $roomClient, UserClient $userClient) {
        $roomId = $room->getId();
        ?>
        <div class="roomName" id="<?php echo $room->getName() ?>">
            <?php echo $room->getName() ?>
        </div>
        <?php
        $users = $roomClient->getUsersInRoom($roomId, $userClient);
        echo "</br>";
        foreach ($users as $user) {
            UserDataDisplay::displayUserSimpleData($user);
            UserDataDisplay::displayRemoveUserButton($user->getId(), $room->getId());
        }
        ?>

        <div class="option" onclick="setAddUserToRoomPOST('<?php echo $roomId?>')">Dodaj ucznia</div>

        <?php
    }

    public static function displayRoomWithId($roomId, RoomClient $roomClient, UserClient $userClient) {
        try {
            $room = $roomClient->getRoomWithId($roomId, $roomClient);
            RoomDisplay::display($room, $roomClient, $userClient);
        } catch (MissingRoomException $e) {
            echo "Pokój nie istnieje.";
        }
    }

    public static function displayAddingUserToRoomForm($roomId) {
        ?>
        <form action="/schule-quizule/" method="post">
            <input type="text" class="form-control" id="addedUserEmail" placeholder="email" name="addedUserEmail" required/>
            <input type="hidden" name="add_user_with_email_to_room" id="add_user_with_email_to_room" value=""/>
            <input type="hidden" name="roomId" id="roomId" value="<?php print "$roomId" ?>"/>
            <input type="submit" name="sendAnswers" value="dodaj ucznia"/>
        </form>

        <?php
    }
}