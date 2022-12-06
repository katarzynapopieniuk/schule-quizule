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
        }
    }

    public static function displayRoomWithId($roomId, RoomClient $roomClient, UserClient $userClient) {
        try {
            $room = $roomClient->getRoomWithId($roomId, $roomClient);
            RoomDisplay::display($room, $roomClient, $userClient);
        } catch (MissingRoomException $e) {
            echo "Pokój nie istnieje.";
        }
    }
}