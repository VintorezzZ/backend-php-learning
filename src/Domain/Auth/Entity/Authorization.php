<?php

namespace VintorezzZ\BackendPhpLearning\Domain\Auth\Entity;

use Ramsey\Uuid\Uuid;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthorizeUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\CheckAuthorizedUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\LogoutUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\RegisterUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\CheckUserExistsUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\DeleteUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\GetUserByLoginUseCase;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;

class Authorization
{
    private const int USERNAME_CHARS_MIN_COUNT = 3;
    private const int USERNAME_CHARS_MAX_COUNT = 40;
    private const int PASSWORD_CHARS_MIN_COUNT = 5;
    private const int PASSWORD_CHARS_MAX_COUNT = 40;

    private GetUserByLoginUseCase $getUserUseCase;
    private DeleteUserUseCase $deleteUserUseCase;
    private CheckUserExistsUseCase $checkUserExistsUseCase;
    private RegisterUseCase $registerUserUseCase;
    private AuthorizeUseCase $authorizeUserUseCase;
    private CheckAuthorizedUseCase $checkUserSessionUseCase;
    private LogoutUseCase $logoutUserUseCase;

    public function __construct(GetUserByLoginUseCase  $getUserUseCase,
                                DeleteUserUseCase      $deleteUserUseCase,
                                CheckUserExistsUseCase $checkUserSessionUseCase,
                                RegisterUseCase        $registerUserUseCase,
                                AuthorizeUseCase       $authorizeUserUseCase,
                                CheckAuthorizedUseCase $getUserSessionUseCase,
                                LogoutUseCase          $logoutUserUseCase)
    {
        $this->getUserUseCase = $getUserUseCase;
        $this->deleteUserUseCase = $deleteUserUseCase;
        $this->checkUserExistsUseCase = $checkUserSessionUseCase;
        $this->registerUserUseCase = $registerUserUseCase;
        $this->authorizeUserUseCase = $authorizeUserUseCase;
        $this->checkUserSessionUseCase = $getUserSessionUseCase;
        $this->logoutUserUseCase = $logoutUserUseCase;
    }

    public function login(string $login, string $password): array
    {
        $result = [];
        $result['error'] = 1;

        $validationCredentialsResult = $this->validateCredentials($login, $password);

        if ($validationCredentialsResult['error']) {
            $result['message'] = $validationCredentialsResult['message'];
            return $result;
        }

        $user = $this->getUserUseCase->execute($login);

        if ($user == null) {
            $result['message'] = 'User not found';
            return $result;
        }

        if ($password != $user->password) {
            $result['message'] = 'Wrong password';
            return $result;
        }

        $this->authorizeUserUseCase->execute($user->id, $login, $password);

        $result['message'] = 'Login successful';
        $result['user'] = $user;
        $result['error'] = 0;
        return $result;
    }

    public function logout(): array
    {
        $result = [];
        $result['error'] = 1;

        $logoutResult = $this->logoutUserUseCase->execute();

        if ($logoutResult === false) {
            $result['message'] = "Failed logout";
            return $result;
        }

        $result['message'] = "Logout success";
        $result['error'] = 0;
        return $result;
    }

    public function register(string $login, string $password): array
    {
        $result = [];
        $result['error'] = 1;

        $validationCredentialsResult = $this->validateCredentials($login, $password);

        if ($validationCredentialsResult['error']) {
            $result['message'] = $validationCredentialsResult['message'];
            return $result;
        }

        if ($this->isUserExists($login)) {
            $result['message'] = 'User already exists';
            return $result;
        }

        //$uId = $this->createUserId($login, $email);

        $addUserResult = $this->addUser($login, $password);

        if (!$addUserResult) {
            $result['message'] = "Error creating user";
            return $result;
        }

        $user = $this->getUserUseCase->execute($login);

        $this->authorizeUserUseCase->execute($user->id, $login, $password);

        $result['userId'] = $user->id;
        $result['message'] = "User created";
        $result['error'] = 0;
        return $result;
    }

    public function getUser(string $username): ?User
    {
        return $this->getUserUseCase->execute($username);
    }

    public function deleteUser(): array
    {
        $result = [];
        $result['error'] = 1;

        if (!isset($_SESSION['login'])) {
            $result['message'] = "You must be logged in to perform this action";
            return $result;
        }

        $user = $this->getUser($_SESSION['login']);

        if ($user == null) {
            $result['message'] = 'User not found';
            return $result;
        }

        $deleteResult = $this->deleteUserUseCase->execute($user->id);

        if ($deleteResult === false) {
            $result['message'] = "Delete user failed";
            return $result;
        }

        $logoutResult = $this->logoutUserUseCase->execute();

        if ($logoutResult === false) {
            $result['message'] = "Delete user failed";
            return $result;
        }

        $result['message'] = "Delete user success";
        $result['error'] = 0;
        return $result;
    }

    public function checkSession(): array
    {
        $result = [];
        $result['error'] = 1;

        $checkResult = $this->checkUserSessionUseCase->execute();

        if ($checkResult === false) {
            $result['message'] = "Session not found";
            return $result;
        }

        $result['message'] = "Session found";
        $result['error'] = 0;
        return $result;
    }

    private function validateCredentials(string $login, string $password): array
    {
        $result = [];
        $result['error'] = 1;

        $usernameValidationResult = $this->validateLogin($login);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $passwordValidationResult = $this->validatePassword($password);

        if ($passwordValidationResult['error']) {
            $result['message'] = $passwordValidationResult['message'];
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    private function validateLogin(string $login): array
    {
        $result = [];
        $result['error'] = 1;

        if (strlen($login) < self::USERNAME_CHARS_MIN_COUNT) {
            $result['message'] = 'Login is too short';
            return $result;
        }

        if (strlen($login) > self::USERNAME_CHARS_MAX_COUNT) {
            $result['message'] = 'Login is too long';
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    private function validatePassword(string $password): array
    {
        $result = [];
        $result['error'] = 1;

        if (strlen($password) < self::PASSWORD_CHARS_MIN_COUNT) {
            $result['message'] = 'Password is too short';
            return $result;
        }

        if (strlen($password) > self::PASSWORD_CHARS_MAX_COUNT) {
            $result['message'] = 'Password is too long';
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    private function isUserExists(string $login): bool
    {
        return (bool)$this->checkUserExistsUseCase->execute($login);
    }

    private function addUser(string $login, string $password): bool
    {
        return $this->registerUserUseCase->execute($login, $password);
    }

    private function createUserId(string $email, string $username): string
    {
        return Uuid::uuid4();
    }
}