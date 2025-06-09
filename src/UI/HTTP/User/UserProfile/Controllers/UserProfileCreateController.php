<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\UserDataValidator;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileCreateUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class UserProfileCreateController
{
    private UserProfileCreateUseCase $createUserProfileUseCase;

    function __construct(UserProfileCreateUseCase $createUserProfileUseCase)
    {
        $this->createUserProfileUseCase = $createUserProfileUseCase;
    }

    public function createUserProfile(int $userId, Request $request): string
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input["data"];

        if (!isset($input['username'])) {
            $result['message'] = "Username is required";
            return json_encode(['result' => $result]);
        }

        if (!isset($input['email'])) {
            $result['message'] = "email is required";
            return json_encode(['result' => $result]);
        }

        $email = $input['email'];
        $emailValidationResult = UserDataValidator::validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return json_encode(['result' => $result]);
        }

        $username = $input['username'];

        $profileCreationResult = $this->createUserProfileUseCase->execute($userId, $username, $email);

        if (!$profileCreationResult) {
            $result['message'] = "User profile creation failed";
            return json_encode(['result' => $result]);
        }

        $result['message'] = "Profile creation success";
        $result['error'] = 0;

        return json_encode(['result' => $result]);
    }
}