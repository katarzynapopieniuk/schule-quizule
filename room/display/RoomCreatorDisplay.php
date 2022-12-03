<?php

class RoomCreatorDisplay {

    public static function displayCreator() {
        ?>
        <form action="/schule-quizule/" method="post">
            <div class="roomCreator">
                <input type="text" class="form-control" id="roomName" placeholder="Nazwa pokoju" name="roomName" required>
            </div>
            <?php
            ?>
            <input type="submit" name="createRoom" value="utwórz pokój">
        </form>
        <?php
    }
}