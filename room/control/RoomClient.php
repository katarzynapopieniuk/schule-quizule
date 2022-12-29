<?php

namespace room\control;

use DatabaseClient;
use room\entity\AddingUserToRoomException;
use room\entity\MissingRoomException;
use room\entity\Room;
use room\entity\RoomCreatingException;
use room\entity\RoomWithNameAlreadyExistsException;
use User;
use UserClient;

class RoomClient {

    public function getRoomsForTeacherId($teacherId) {
        $getRoomWithTeacherIdQuery = "SELECT * from room where teacherId = $teacherId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getRoomWithTeacherIdQuery);

        $rooms = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row["name"];
                $id = $row["id"];
                $room = new Room($id, $name, $teacherId);
                $rooms[] = $room;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $rooms;
    }

    public function getUsersInRoom($roomId, UserClient $userClient) {
        $users = $this->getSimpleUsersInRoom($roomId);
        foreach ($users as $user) {
            $userClient->setUserData($user);
        }
        return $users;
    }

    public function createRoom($roomName, $teacherId) {
        $databaseConnection = DatabaseClient::openConnection();
        $this->validateRoomName($teacherId, $roomName, $databaseConnection);
        $this->createRoomWithName($roomName, $teacherId, $databaseConnection);
        DatabaseClient::closeConnection($databaseConnection);
    }

    public function getRoomWithId($roomId) {
        $getRoomWithIdQuery = "SELECT * from room where id = $roomId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getRoomWithIdQuery);


        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $name = $row["name"];
            $teacherId = $row['teacherId'];
            DatabaseClient::closeConnection($databaseConnection);
            return new Room($roomId, $name, $teacherId);
        }

        DatabaseClient::closeConnection($databaseConnection);
        throw new MissingRoomException();
    }

    public function addUserToRoom($userId, $roomId) {
        $databaseConnection = DatabaseClient::openConnection();
        $createAddUserToRoomQuery = "INSERT INTO room_user (roomId, userId) VALUES ('$roomId', '$userId')";
        $result = mysqli_query($databaseConnection, $createAddUserToRoomQuery);

        if (!$result) {
            DatabaseClient::closeConnection($databaseConnection);
            throw new AddingUserToRoomException("Cannot add user to room, probably cause: user already in room");
        }
        DatabaseClient::closeConnection($databaseConnection);
    }

    public function isUserInRoom($userId, $roomId): bool {
        $databaseConnection = DatabaseClient::openConnection();
        $userIsAlreadyInRoomQuery = "SELECT count(*) as total from room_user where roomId='$roomId' and userId='$userId'";
        $result = mysqli_query($databaseConnection, $userIsAlreadyInRoomQuery);
        $data = $result->fetch_assoc();
        DatabaseClient::closeConnection($databaseConnection);
        return $data['total'] > 0;
    }

    public function removeUserFromRoom($userId, $roomId) {
        $databaseConnection = DatabaseClient::openConnection();
        $userIsAlreadyInRoomQuery = "DELETE FROM room_user WHERE roomId='$roomId' and userId='$userId'";
        mysqli_query($databaseConnection, $userIsAlreadyInRoomQuery);
        DatabaseClient::closeConnection($databaseConnection);
    }

    public function getRoomsForUserId($userId) {
        $getRoomWithUserIdQuery = "SELECT roomId from room_user where userId = $userId";
        $databaseConnection = DatabaseClient::openConnection();

        $result = mysqli_query($databaseConnection, $getRoomWithUserIdQuery);

        $roomsIds = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $roomId = $row["roomId"];
                $roomsIds[] = $roomId;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);

        $rooms = array();
        foreach($roomsIds as $roomId) {
            $rooms[] = $this->getRoomWithId($roomId);
        }

        return $rooms;
    }

    /**
     * @param $roomId
     * @return array
     */
    private function getSimpleUsersInRoom($roomId) {
        $databaseConnection = DatabaseClient::openConnection();
        $getUserIdWithRoomIdQuery = "SELECT userId from room_user where roomId = $roomId";

        $result = mysqli_query($databaseConnection, $getUserIdWithRoomIdQuery);

        $users = array();

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userId = $row["userId"];
                $user = new User($userId);
                $users[] = $user;
            }
        }

        DatabaseClient::closeConnection($databaseConnection);
        return $users;
    }

    /**
     * @param $teacherId
     * @param $roomName
     * @param \mysqli $databaseConnection
     * @return void
     */
    private function validateRoomName($teacherId, $roomName, \mysqli $databaseConnection) {
        $getRoomQuery = "SELECT * from room where teacherId = '$teacherId' and name = '$roomName'";
        $result = mysqli_query($databaseConnection, $getRoomQuery);
        if (mysqli_num_rows($result) > 0) {
            DatabaseClient::closeConnection($databaseConnection);
            throw new RoomWithNameAlreadyExistsException();
        }
    }

    /**
     * @param $roomName
     * @param $teacherId
     * @param \mysqli $databaseConnection
     * @return void
     */
    private function createRoomWithName($roomName, $teacherId, \mysqli $databaseConnection) {
        $createRoomQuery = "INSERT INTO room (name, teacherId) VALUES ('$roomName', '$teacherId')";
        $result = mysqli_query($databaseConnection, $createRoomQuery);

        if (!$result) {
            DatabaseClient::closeConnection($databaseConnection);
            throw new RoomCreatingException();
        }
    }
}