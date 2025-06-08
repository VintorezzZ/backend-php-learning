<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Entity;

use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity\UserProfile;

class User
{
    public readonly int $id;
    public readonly string $login;
    public readonly string $password;
    public readonly UserProfile $profile;

    function __construct(int $id, string $login, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
    }

    public function setProfile(UserProfile $profile): void
    {
        $this->profile = $profile;
    }
}