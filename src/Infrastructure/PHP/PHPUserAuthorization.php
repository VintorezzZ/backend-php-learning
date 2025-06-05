<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\PHP;

use Ramsey\Uuid\Uuid;
use VintorezzZ\BackendPhpLearning\Application\User\AuthorizeUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\GetUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\RegisterUserUseCase;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\IAuthorization;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class PHPUserAuthorization implements IAuthorization
{
    private const int USERNAME_CHARS_MIN_COUNT = 3;
    private const int USERNAME_CHARS_MAX_COUNT = 40;
    private const int PASSWORD_CHARS_MIN_COUNT = 5;
    private const int PASSWORD_CHARS_MAX_COUNT = 40;

    private IUserRepository $userRepository;
    private GetUserUseCase $getUserUseCase;
    private RegisterUserUseCase $registerUserUseCase;
    private AuthorizeUserUseCase $authorizeUserUseCase;

    public function __construct(IUserRepository $userRepository,
                                GetUserUseCase $getUserUseCase,
                                RegisterUserUseCase $registerUserUseCase,
                                AuthorizeUserUseCase $authorizeUserUseCase)
    {
        $this->userRepository = $userRepository;
        $this->getUserUseCase = $getUserUseCase;
        $this->registerUserUseCase = $registerUserUseCase;
        $this->authorizeUserUseCase = $authorizeUserUseCase;
    }

    public function login(string $username, string $password): array
    {
        $result = [];
        $result['error'] = true;

        $usernameValidationResult = $this->validateUsername($username);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $passwordValidationResult = $this->validatePassword($password);

        if ($passwordValidationResult['error']) {
            $result['message'] = $passwordValidationResult['message'];
            return $result;
        }

        $user = $this->getUserUseCase->execute($username);

        if ($user == null) {
            $result['message'] = 'User not found';
            return $result;
        }

        if ($password != $user->password) {
            $result['message'] = 'Wrong password';
            return $result;
        }

        $token = $this->authorizeUserUseCase->authorize($user);

        $result['token'] = $token;
        $result['message'] = 'Login successful';
        $result['user'] = $user;
        $result['error'] = false;
        return $result;
    }

    public function logout(string $sessionId): bool
    {
        // TODO: Implement logout() method.
    }

    public function register(string $email, string $username, string $password): array
    {
        $result = [];
        $result['error'] = true;

        $usernameValidationResult = $this->validateUsername($username);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $passwordValidationResult = $this->validatePassword($password);

        if ($passwordValidationResult['error']) {
            $result['message'] = $passwordValidationResult['message'];
            return $result;
        }

        $emailValidationResult = $this->validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return $result;
        }

        if ($this->isUserExists($email)) {
            $result['message'] = 'Email already taken';
            return $result;
        }

        $uId = $this->createUserId($email, $username);

        $addUserResult = $this->addUser($uId, $email, $username, $password);

        if (!$addUserResult) {
            $result['message'] = "Error creating user";
            return $result;
        }

        $result['message'] = "User created";
        $result['error'] = false;
        return $result;
    }

    public function getUser(int $userId): User
    {
        // TODO: Implement getUser() method.
    }

    public function deleteUser(int $userId, string $password): bool
    {
        // TODO: Implement deleteUser() method.
    }

    private function validateUserName(string $username): array
    {
        $result = [];
        $result['error'] = true;

        if (strlen($username) < self::USERNAME_CHARS_MIN_COUNT) {
            $result['message'] = 'Username is too short';
            return $result;
        }

        if (strlen($username) > self::USERNAME_CHARS_MAX_COUNT) {
            $result['message'] = 'Username is too long';
            return $result;
        }

        $result['error'] = false;
        return $result;
    }

    private function validatePassword(string $password): array
    {
        $result = [];
        $result['error'] = true;

        if (strlen($password) < self::PASSWORD_CHARS_MIN_COUNT) {
            $result['message'] = 'Password is too short';
            return $result;
        }

        if (strlen($password) > self::PASSWORD_CHARS_MAX_COUNT) {
            $result['message'] = 'Password is too long';
            return $result;
        }

        $result['error'] = false;
        return $result;
    }

    private function validateEmail(string $email): array
    {
        $result = [];
        $result['error'] = true;

        if (!filter_var($email, FILTER_SANITIZE_EMAIL) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result['message'] = 'Invalid email';
            return $result;
        }

        $result['error'] = false;
        return $result;
    }

    private function isUserExists(string $email): bool
    {
        return (bool)$this->userRepository->exists($email);
    }

    private function addUser(string $uId, string $email, string $username, string $password): bool
    {
        return $this->registerUserUseCase->execute($uId, $email, $username, $password);
    }

    private function createUserId(string $email, string $username): string
    {
        return Uuid::uuid4();
    }
}