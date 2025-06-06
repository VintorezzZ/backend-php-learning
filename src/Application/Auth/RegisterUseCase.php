<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class RegisterUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $login, string $email, string $password): bool
    {
        return $this->userRepository->add($login, $email, $password);
    }
}