<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use VintorezzZ\BackendPhpLearning\Domain\Auth\Entity\Authorization;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class AuthController
{
    private Authorization $authorization;

    function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    public function login(Request $request): string
    {
        /*$token = $request->headers['Authorization'] ?? null;

        if ($token !== null) {
            return json_encode(['result' => ['error' => 1]]);
        }*/

        // ищем в базе токен. если нашли, то ошибка авторизации (уже авторизован)

        $input = json_decode($request->content, true);
        $input = $input['data'] ?? null;
        $result = $this->authorization->Login($input["login"], $input["password"]);
        return json_encode(['result' => $result]);
    }

    public function register(Request $request): array
    {
        $input = json_decode($request->content, true);
        $input = $input["data"];
        $result = $this->authorization->register($input['login'], $input['password']);
        return $result;
    }

    public function logout(): string
    {
        $result = $this->authorization->logout();
        return json_encode(['result' => $result]);
    }

    public function deleteUser(Request $request): string
    {
        //$input = json_decode($request->content, true);
        //$input = $input['data'] ?? null;
        //$token = $input["token"] ?? null;
        $result = $this->authorization->deleteUser();
        return json_encode(['result' => $result]);
    }

    public function checkSession(): string
    {
        $result = $this->authorization->checkSession();
        return json_encode(['result' => $result]);
    }
}