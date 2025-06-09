<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthAuthorizeUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\UserDataValidator;
use VintorezzZ\BackendPhpLearning\Application\User\UserGetByLoginUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class AuthAuthorizeController
{
    private AuthAuthorizeUseCase $authorizeUseCase;
    private UserGetByLoginUseCase $getUserByLoginUseCase;

    function __construct(AuthAuthorizeUseCase $authorizeUseCase, UserGetByLoginUseCase $getUserByLoginUseCase)
    {
        $this->authorizeUseCase = $authorizeUseCase;
        $this->getUserByLoginUseCase = $getUserByLoginUseCase;
    }

    public function login(Request $request): string
    {
        $result = [];
        $result['error'] = 1;

        /*$token = $request->headers['Authorization'] ?? null;

        if ($token !== null) {
            return json_encode(['result' => ['error' => 1]]);
        }*/

        // ищем в базе токен. если нашли, то ошибка авторизации (уже авторизован)

        $input = json_decode($request->content, true);

        $login = $input["login"];
        $password = $input["password"];

        $validationCredentialsResult = UserDataValidator::validateCredentials($login, $password);

        if ($validationCredentialsResult['error']) {
            $result['message'] = $validationCredentialsResult['message'];
            return json_encode(['result' => $result]);
        }

        $user = $this->getUserByLoginUseCase->execute($login);

        if ($user == null) {
            $result['message'] = 'User not found';
            return json_encode(['result' => $result]);
        }

        if ($password != $user->password) {
            $result['message'] = 'Wrong password';
            return json_encode(['result' => $result]);
        }

        $this->authorizeUseCase->execute($user->id, $login, $password);

        $result['message'] = 'Login successful';
        $result['error'] = 0;
        return json_encode(['result' => $result]);
    }
}