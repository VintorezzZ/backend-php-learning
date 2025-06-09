<?php

namespace VintorezzZ\BackendPhpLearning\Application\User\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserProfileRepository;
use VintorezzZ\BackendPhpLearning\Domain\User\ValueObject\UserProfile;

class UserProfileGetByUserIdUseCase
{
    private IUserProfileRepository $userProfileRepository;

    function __construct(IUserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function execute(): ?UserProfile
    {
        $userId = $_SESSION['userId'];
        return $this->userProfileRepository->getProfile($userId);
    }
}