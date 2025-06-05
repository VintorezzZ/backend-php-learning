<?php

namespace VintorezzZ\BackendPhpLearning\Application\User;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class RegisterUserUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $uId, string $email, string $username, string $password): bool
    {
        return $this->userRepository->add($uId, $email, $username, $password);
    }
}