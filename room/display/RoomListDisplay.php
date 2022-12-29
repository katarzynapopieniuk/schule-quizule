<?php

namespace room\display;
use room\control\RoomDisplay;

class RoomListDisplay {

    public static function displayRoomList(array $rooms) {
        foreach ($rooms as $room) {
            RoomDisplay::displaySetRoomButton($room->getId(), $room->getName());
        }
    }
}