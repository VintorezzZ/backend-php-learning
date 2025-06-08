<?php

namespace VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity;

class UserProfile
{
    public readonly string $username;
    public readonly string $email;

    function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }
}