<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class CheckAuthorizedUseCase
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