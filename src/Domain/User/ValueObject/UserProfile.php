<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\ValueObject;

class UserProfile // ValueObject of User
{
    public readonly int $userId;
    public readonly string $username;
    public readonly string $email;

    function __construct(int $userId, string $username, string $email)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->email = $email;
    }
}