<?php

class UserDataDisplay {

    public static function displayDataForUserWithId($userId, UserClient $userClient) {
        $user = self::getUserWithId($userId, $userClient);
        self::displayUserData($user);
    }

    public static function displayUserSimpleData(User $user) {
        echo "<div class='user_data'>";
        echo $user->getName() . " " .  $user->getSurname();
        echo "</div>";
    }

    public static function displayRemoveUserButton($userId, $roomId) {
        ?>
        <form action="/schule-quizule/" method="post">
            <input type="hidden" name="roomId" id="roomId" value="<?php print "$roomId" ?>"/>
            <input type="hidden" name="userId" id="roomId" value="<?php print "$userId" ?>"/>
            <input type="submit" name="removeUserFromRoom" value="usuń">
        </form>
        <?php
    }

    /**
     * @param $userId
     * @param UserClient $userClient
     * @return User
     */
    private static function getUserWithId($userId, UserClient $userClient) {
        $user = new User($userId);
        $userClient->setUserData($user);
        return $user;
    }

    private static function displayUserData($user) {
        echo "<div class='user_data'>";
         self::displaySingleData("Imię", $user->getName());
         self::displaySingleData("Nazwisko", $user->getSurname());
         self::displaySingleData("Email", $user->getEmail());
         self::displaySingleData("Typ konta", $user->getAccountType());
         self::displaySingleData("Klucz konta", $user->getAccountKey());
        echo "</div>";
    }

    private static function displaySingleData($dataName, $dataValue) {
        echo "<div class='user_single_data'>";
        echo $dataName . " : " . $dataValue . "</br>";
        echo "</div>";
    }
}