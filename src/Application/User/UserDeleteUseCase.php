<?php

namespace VintorezzZ\BackendPhpLearning\Application\User;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class UserDeleteUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id): bool
    {
        return $this->userRepository->deleteUser($id);
    }
}