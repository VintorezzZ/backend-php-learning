<?php

namespace VintorezzZ\BackendPhpLearning\Application\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity\UserProfile;
use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Repository\IUserProfileRepository;

class GetUserProfileByUserIdUseCase
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