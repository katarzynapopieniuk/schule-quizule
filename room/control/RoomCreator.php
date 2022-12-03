<?php

namespace room\control;

use room\entity\RoomCreatingException;
use room\entity\RoomWithNameAlreadyExistsException;

class RoomCreator {

    public static function createRoom($roomName, $teacherId, RoomClient $roomClient) {
        try {
            $roomClient->createRoom($roomName, $teacherId);
        } catch (RoomWithNameAlreadyExistsException $e) {
            echo "<div class='error'>Pokój tej nazwie już istnieje!</div>";
            return;
        } catch (RoomCreatingException $e) {
            echo "<div class='error'>Nie udało się utworzyć pokoju. Spróbuj ponownie.</div>";
            return;
        }
        echo "Pokój utworzony.";
    }
}