<?php

namespace VintorezzZ\BackendPhpLearning\Application\User;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class GetUserByLoginUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(string $login): ?User
    {
        return $this->userRepository->getUserByLogin($login);
    }
}