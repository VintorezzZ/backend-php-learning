<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers;

use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileUpdateUsernameUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class UserProfileUpdateUsernameController
{
    private UserProfileUpdateUsernameUseCase $updateProfileUsernameUseCase;

    function __construct(UserProfileUpdateUsernameUseCase $updateProfileUsernameUseCase)
    {
        $this->updateProfileUsernameUseCase = $updateProfileUsernameUseCase;
    }

    public function updateProfileUsername(Request $request): string
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);

        if (!isset($input['username'])) {
            $result['message'] = "username is required";
            return json_encode(['result' => $result]);
        }

        $userId = $_SESSION['userId'];
        $username = $input['username'];

        $updateResult = $this->updateProfileUsernameUseCase->execute($userId, $username);

        if (!$updateResult) {
            $result['message'] = "User profile update failed";
            return json_encode(['result' => $result]);
        }

        $result['error'] = 0;
        $result['message'] = "Profile update success";
        return json_encode(['result' => $result]);
    }
}