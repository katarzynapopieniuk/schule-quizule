<?php

class AccountType {

    const TEACHER = "teacher";
    const USER = "user";
    const ADMIN="admin";

    static function isTeacher($accountType) {
        return strcmp(self::TEACHER, $accountType) == 0;
    }

    static function isUser($accountType) {
        return strcmp(self::USER, $accountType) == 0;
    }

    static function isAdmin($accountType) {
        return strcmp(self::ADMIN, $accountType) == 0;
    }
}