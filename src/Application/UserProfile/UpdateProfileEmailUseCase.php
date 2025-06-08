<?php

namespace VintorezzZ\BackendPhpLearning\Application\UserProfile;

use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Repository\IUserProfileRepository;

class UpdateProfileEmailUseCase
{
    private IUserProfileRepository $userProfileRepository;

    function __construct(IUserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
    }

    public function execute(int $userId, string $email): bool
    {
        return $this->userProfileRepository->updateUsername($userId, $email);
    }
}