<?php


namespace Api\DomainServices\User;


use Api\Models\User;

class UserService
{
    public static function getUserById(int $userId)
    {
        return User::getUserById($userId);
    }

}