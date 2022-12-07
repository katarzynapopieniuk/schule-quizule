<?php

class UserClient {

    public function setUserData(User $user): User {
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

    public function getUserIdForEmail($email) {
        $getUserIdWithEmailQuery = "SELECT id from user where email = '$email'";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getUserIdWithEmailQuery);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $id = $row["id"];
            DatabaseClient::closeConnection($databaseConnection);
            return $id;
        }

        DatabaseClient::closeConnection($databaseConnection);
        throw new MissingUserException();
    }
}