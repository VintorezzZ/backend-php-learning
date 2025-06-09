<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

class AuthCheckAuthorizedUseCase
{

    function __construct()
    {
    }

    public function execute(): bool
    {
        if (isset($_SESSION['login'])) {
            return true;
        }

        return false;
    }
}