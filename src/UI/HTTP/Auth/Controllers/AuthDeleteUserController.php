<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthLogoutUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserDeleteUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserGetByLoginUseCase;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;

class AuthDeleteUserController
{
    private UserDeleteUseCase $deleteUserUseCase;
    private AuthLogoutUseCase $logoutUserUseCase;
    private UserGetByLoginUseCase $getUserUseCase;

    function __construct(UserDeleteUseCase     $deleteUserUseCase,
                         UserGetByLoginUseCase $getUserByLoginUseCase,
                         AuthLogoutUseCase     $logoutUserUseCase)
    {

        $this->deleteUserUseCase = $deleteUserUseCase;
        $this->logoutUserUseCase = $logoutUserUseCase;
        $this->getUserUseCase = $getUserByLoginUseCase;
    }

    public function deleteUser(): string
    {
        $result = [];
        $result['error'] = 1;

        //$input = json_decode($request->content, true);
        //$input = $input['data'] ?? null;
        //$token = $input["token"] ?? null;

        if (!isset($_SESSION['login'])) {
            $result['message'] = "You must be logged in to perform this action";
            return json_encode(['result' => $result]);
        }

        $user = $this->getUser($_SESSION['login']);

        if ($user == null) {
            $result['message'] = 'User not found';
            return json_encode(['result' => $result]);
        }

        $deleteResult = $this->deleteUserUseCase->execute($user->id);

        if ($deleteResult === false) {
            $result['message'] = "Delete user failed";
            return json_encode(['result' => $result]);
        }

        $logoutResult = $this->logoutUserUseCase->execute();

        if ($logoutResult === false) {
            $result['message'] = "Delete user failed";
            return json_encode(['result' => $result]);
        }

        $result['message'] = "Delete user success";
        $result['error'] = 0;
        return json_encode(['result' => $result]);
    }

    public function getUser(string $login): ?User
    {
        return $this->getUserUseCase->execute($login);
    }
}