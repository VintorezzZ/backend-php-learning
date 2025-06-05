<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\User\Controllers;

use VintorezzZ\BackendPhpLearning\Domain\User\IAuthorization;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class AuthUserController
{
    private IAuthorization $authorization;

    function __construct(IAuthorization $authorization)
    {
        $this->authorization = $authorization;
    }

    public function login(Request $request): string
    {
        $token = $request->headers['Authorization'] ?? null;

        if ($token !== null) {
            return json_encode(['result' => ['error' => 1]]);
        }

        // ищем в базе токен. если нашли, то ошибка авторизации (уже авторизован)

        $input = json_decode($request->content, true);
        $input = $input['data'] ?? null;
        $result = $this->authorization->Login($input["username"], $input["password"]);
        return json_encode(['result' => $result]);
    }

    public function register(Request $request): string
    {
        $input = json_decode($request->content, true);
        $input = $input["data"];
        $result = $this->authorization->register($input['email'], $input['username'], $input['password']);
        return json_encode(['result' => $result]);
    }

    public function logout(Request $request): string
    {
// если фронт отправил токен, то его нужно удалить из бд.
// фронт тоже его удаляет
    }

    public function deleteUser(Request $request): string
    {

    }
}