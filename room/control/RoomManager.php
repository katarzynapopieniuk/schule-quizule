<?php

namespace room\control;

use AccountType;
use MissingUserException;
use room\entity\AddingUserToRoomException;
use UserClient;

class RoomManager {

    public static function addUserToRoom($userEmail, $roomId, RoomClient $roomClient, UserClient $userClient) {
        try {
            self::addUserToRoomIfPossible($userEmail, $roomId, $roomClient, $userClient);
            echo "Uczeń został dodany dodany";
        } catch (AddingUserToRoomException $e) {
            echo "Nie można dodać użytkownika do pokoju, ponieważ: " . $e->getMessage();
        } catch (MissingUserException $e) {
            echo "Nie można dodać użytkownika do pokoju, ponieważ użytkownik o podanym adresie email nie istnieje";
        }
    }

    private static function addUserToRoomIfPossible($userEmail, $roomId, RoomClient $roomClient, UserClient $userClient) {
        self::validateIfCurrentUserHasProperAccountType();
        self::validateIfCurrentUserIsRoomOwner($roomClient, $roomId);
        $userId = $userClient->getUserIdForEmail($userEmail);
        self::validaIfUserIsAlreadyInRoom($userId, $roomId, $roomClient);
        $roomClient->addUserToRoom($userId, $roomId);
    }

    /**
     * @return void
     */
    private static function validateIfCurrentUserHasProperAccountType() {
        $accountType = $_SESSION['accountType'];
        if (!AccountType::isTeacher($accountType)) {
            throw new AddingUserToRoomException("tylko nauczyciel może zapraszać do pokoju");
        }
    }

    /**
     * @param $roomClient
     * @param $roomId
     * @return void
     */
    private static function validateIfCurrentUserIsRoomOwner($roomClient, $roomId) {
        $teacherId = $_SESSION['Id'];
        $teachersRooms = $roomClient->getRoomsForTeacherId($teacherId);
        if (!self::teacherOwnRoomWithId($roomId, $teachersRooms)) {
            throw new AddingUserToRoomException("nauczyciel może zapraszać do pokoju tylko, jeśli jest jego właścicielem");
        }
    }

    private static function teacherOwnRoomWithId($roomId, $teachersRooms): bool {
        foreach ($teachersRooms as $teacherRoom) {
            if(strcmp($roomId, $teacherRoom->getId()) == 0) {
                return true;
            }
        }
        return false;
    }

    private static function validaIfUserIsAlreadyInRoom($userId, $roomId, RoomClient $roomClient) {
        if($roomClient->isUserInRoom($userId, $roomId)) {
            throw new AddingUserToRoomException("uczeń już jest w pokoju");
        }
    }
}