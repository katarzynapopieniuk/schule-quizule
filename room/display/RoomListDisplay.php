<?php

namespace room\display;
class RoomListDisplay {

    public static function displayRoomList(array $rooms) {
        foreach ($rooms as $room): ?>
            <div class="roomName" id="<?php echo $room->getName() ?>"
                 onclick="setCurrentRoomPOST('<?php echo $room->getId() ?>')">
                <?php echo $room->getName() ?>
            </div>
        <?php endforeach;
    }
}