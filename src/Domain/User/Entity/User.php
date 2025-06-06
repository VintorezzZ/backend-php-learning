<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Entity;

class User
{
    public readonly int $id;
    public readonly string $login;
    public readonly string $email;
    public readonly string $password;

    function __construct(int $id, string $login, string $email, string $password)
    {
        $this->id = $id;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
    }
}