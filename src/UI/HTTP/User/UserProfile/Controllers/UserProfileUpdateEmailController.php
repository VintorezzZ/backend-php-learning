<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\UserDataValidator;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileUpdateEmailUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class UserProfileUpdateEmailController
{
    private UserProfileUpdateEmailUseCase $updateProfileEmailUseCase;

    function __construct(UserProfileUpdateEmailUseCase $updateProfileEmailUseCase)
    {
        $this->updateProfileEmailUseCase = $updateProfileEmailUseCase;
    }

    public function updateProfileEmail(Request $request): string
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input["data"];

        if (!isset($input['email'])) {
            $result['message'] = "Email is required";
            return json_encode(['result' => $result]);
        }

        $email = $input['email'];
        $emailValidationResult = UserDataValidator::validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return json_encode(['result' => $result]);
        }

        $userId = $_SESSION['userId'];

        return $this->updateProfileEmailUseCase->execute($userId, $email);
    }
}