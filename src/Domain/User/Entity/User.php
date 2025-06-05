<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Entity;

class User
{
    public readonly string $id;
    public readonly string $uId;
    public readonly string $name;
    public readonly string $email;
    public readonly string $password;

    function __construct(int $id, string $uId, string $username, string $email, string $password)
    {
        $this->id = $id;
        $this->uId = $uId;
        $this->name = $username;
        $this->email = $email;
        $this->password = $password;
    }
}