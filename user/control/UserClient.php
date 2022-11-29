<?php

class UserClient {

    function setUserData($user) {
        $userId = $user->getId();
        $getUserWithIdQuery = "SELECT * from user where id = $userId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getUserWithIdQuery);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $user->setEmail($row["email"]);
                $user->setAccountType($row["accountType"]);
                $user->setName($row["name"]);
                $user->setSurname($row["surname"]);
                $user->setAccountKey($row["accountKey"]);
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $user;
    }
}