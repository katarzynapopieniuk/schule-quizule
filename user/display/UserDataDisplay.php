<?php

class UserDataDisplay {

    public static function displayDataForUserWithId($userId, $userClient) {
        $user = self::getUserWithId($userId, $userClient);
        self::displayUserData($user);
    }

    /**
     * @param $userId
     * @param $userClient
     * @return User
     */
    private static function getUserWithId($userId, $userClient) {
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