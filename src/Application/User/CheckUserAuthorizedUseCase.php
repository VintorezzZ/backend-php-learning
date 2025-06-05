<?php

namespace VintorezzZ\BackendPhpLearning\Application\User;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class CheckUserAuthorizedUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): bool
    {
        if (isset($_SESSION['login'])) {
            return true;
        }

        return false;
    }
}