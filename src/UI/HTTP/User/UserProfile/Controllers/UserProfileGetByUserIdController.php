<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers;

use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileGetByUserIdUseCase;

class UserProfileGetByUserIdController
{
    private UserProfileGetByUserIdUseCase $userProfileGetByUserIdUseCase;

    function __construct(UserProfileGetByUserIdUseCase $userProfileGetByUserIdUseCase)
    {
        $this->userProfileGetByUserIdUseCase = $userProfileGetByUserIdUseCase;
    }

    public function getUserProfile(): string
    {
        $result = [];
        $result['error'] = 1;

        $profile = $this->userProfileGetByUserIdUseCase->execute();

        if (!$profile) {
            $result['message'] = "User profile not found";
            return json_encode(['result' => $result]);
        }

        $result['error'] = 0;
        $result['profile'] = $profile;
        return json_encode(['result' => $result]);
    }
}