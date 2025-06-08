<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\UserProfile\Controllers;

use VintorezzZ\BackendPhpLearning\Application\UserProfile\CreateUserProfileUseCase;
use VintorezzZ\BackendPhpLearning\Application\UserProfile\GetUserProfileByUserIdUseCase;
use VintorezzZ\BackendPhpLearning\Application\UserProfile\UpdateProfileEmailUseCase;
use VintorezzZ\BackendPhpLearning\Application\UserProfile\UpdateProfileUsernameUseCase;
use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity\UserProfile;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class UserProfileController
{
    private const int USERNAME_CHARS_MIN_COUNT = 3;
    private const int USERNAME_CHARS_MAX_COUNT = 40;

    private CreateUserProfileUseCase $createUserProfileUseCase;
    private GetUserProfileByUserIdUseCase $getUserProfileUseCase;
    private UpdateProfileUsernameUseCase $updateProfileUsernameUseCase;
    private UpdateProfileEmailUseCase $updateProfileEmailUseCase;

    function __construct(CreateUserProfileUseCase      $createUserProfileUseCase,
                         GetUserProfileByUserIdUseCase $getUserProfileUseCase,
                         UpdateProfileUsernameUseCase  $updateProfileUsernameUseCase,
                         UpdateProfileEmailUseCase     $updateProfileEmailUseCase)
    {
        $this->createUserProfileUseCase = $createUserProfileUseCase;
        $this->getUserProfileUseCase = $getUserProfileUseCase;
        $this->updateProfileUsernameUseCase = $updateProfileUsernameUseCase;
        $this->updateProfileEmailUseCase = $updateProfileEmailUseCase;
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
        $emailValidationResult = $this->validateEmail($email);

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

    public function getUserProfile(): string
    {
        $result = [];
        $result['error'] = 1;

        $profile = $this->getUserProfileUseCase->execute();

        if (!$profile) {
            $result['message'] = "User profile not found";
            return json_encode(['result' => $result]);
        }

        $result['error'] = 0;
        $result['profile'] = $profile;
        return json_encode(['result' => $result]);

    }

    public function updateProfileUsername(Request $request): string
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input["data"];

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
        $emailValidationResult = $this->validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return json_encode(['result' => $result]);
        }

        $userId = $_SESSION['userId'];

        return $this->updateProfileEmailUseCase->execute($userId, $email);
    }

    private function validateEmail(string $email): array
    {
        $result = [];
        $result['error'] = 1;

        if (!filter_var($email, FILTER_SANITIZE_EMAIL) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = 'Invalid email';
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    private function validateUsername(string $username): array
    {
        $result = [];
        $result['error'] = 1;

        if (strlen($username) < self::USERNAME_CHARS_MIN_COUNT) {
            $result['message'] = 'Username is too short';
            return $result;
        }

        if (strlen($username) > self::USERNAME_CHARS_MAX_COUNT) {
            $result['message'] = 'Username is too long';
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    public function validateRegisterProfileData(Request $request): array{
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input['data'] ?? null;

        $username = $input['username'];
        $email = $input['email'];

        $usernameValidationResult = $this->validateUsername($username);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $emailValidationResult = $this->validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }
}