<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use Ramsey\Uuid\Uuid;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthAuthorizeUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthRegisterUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\UserDataValidator;
use VintorezzZ\BackendPhpLearning\Application\User\UserExistsCheckUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserGetByLoginUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class AuthRegisterController
{
    private UserGetByLoginUseCase $getUserByLoginUseCase;
    private AuthAuthorizeUseCase $authorizeUserUseCase;
    private UserExistsCheckUseCase $checkUserExistsUseCase;
    private AuthRegisterUseCase $registerUserUseCase;

    function __construct(AuthRegisterUseCase    $registerUseCase,
                         UserExistsCheckUseCase $checkUserExistsUseCase,
                         UserGetByLoginUseCase  $getUserByLoginUseCase,
                         AuthAuthorizeUseCase   $authorizeUserUseCase)
    {

        $this->getUserByLoginUseCase = $getUserByLoginUseCase;
        $this->authorizeUserUseCase = $authorizeUserUseCase;
        $this->checkUserExistsUseCase = $checkUserExistsUseCase;
        $this->registerUserUseCase = $registerUseCase;
    }

    public function register(Request $request): array
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input["data"];
        $login = $input["login"];
        $password = $input["password"];

        $validationCredentialsResult = UserDataValidator::validateCredentials($login, $password);

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

        $user = $this->getUserByLoginUseCase->execute($login);

        $this->authorizeUserUseCase->execute($user->id, $login, $password);

        $result['userId'] = $user->id;
        $result['message'] = "User created";
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