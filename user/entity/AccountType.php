<?php

class AccountType {

    const TEACHER = "teacher";
    const USER = "user";

    static function isTeacher($accountType) {
        return strcmp(self::TEACHER, $accountType) == 0;
    }

    static function isUser($accountType) {
        return strcmp(self::USER, $accountType) == 0;
    }
}