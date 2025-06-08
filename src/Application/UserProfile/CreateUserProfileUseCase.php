<?php

namespace VintorezzZ\BackendPhpLearning\Application\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Repository\IUserProfileRepository;

class CreateUserProfileUseCase
{
    private IUserProfileRepository $userProfileRepository;

    function __construct(IUserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function execute(int $userId, string $username, string $email): bool
    {
        return $this->userProfileRepository->createProfile($userId, $username, $email);
    }
}