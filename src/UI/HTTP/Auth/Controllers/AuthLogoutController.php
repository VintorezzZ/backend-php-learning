<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthLogoutUseCase;

class AuthLogoutController
{
    private AuthLogoutUseCase $logoutUseCase;

    public function __construct(AuthLogoutUseCase $logoutUseCase)
    {
        $this->logoutUseCase = $logoutUseCase;
    }

    public function logout(): string
    {
        $result = [];
        $result['error'] = 1;

        $logoutResult = $this->logoutUseCase->execute();

        if ($logoutResult === false) {
            $result['message'] = "Failed logout";
            return json_encode(['result' => $result]);
        }

        $result['message'] = "Logout success";
        $result['error'] = 0;
        return json_encode(['result' => $result]);
    }
}