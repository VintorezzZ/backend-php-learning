<?php

namespace VintorezzZ\BackendPhpLearning\Application\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity\UserProfile;
use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Repository\IUserProfileRepository;

class UpdateProfileUsernameUseCase
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