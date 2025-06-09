<?php

namespace VintorezzZ\BackendPhpLearning\Application\User\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserProfileRepository;

class UserProfileUpdateUsernameUseCase
{
    private IUserProfileRepository $userProfileRepository;

    function __construct(IUserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function execute(int $userId, string $username): bool
    {
        return $this->userProfileRepository->updateUsername($userId, $username);
    }
}