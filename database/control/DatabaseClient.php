<?php

class DatabaseClient {

    const HOST = "localhost";
    const USER = "root";
    const PASSWORD = "";
    const DATABASE = "schule_quizule";

    static function openConnection() {
        $connection = new mysqli(self::HOST, self::USER, self::PASSWORD, self::DATABASE);
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }

        return $connection;
    }

    static function closeConnection($conn) {
        $conn -> close();
    }
}