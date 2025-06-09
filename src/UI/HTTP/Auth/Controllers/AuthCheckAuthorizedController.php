<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthCheckAuthorizedUseCase;

class AuthCheckAuthorizedController
{
    private AuthCheckAuthorizedUseCase $checkAuthorizedUseCase;

    public function __construct(AuthCheckAuthorizedUseCase $checkAuthorizedUseCase)
    {
        $this->checkAuthorizedUseCase = $checkAuthorizedUseCase;
    }

    public function checkSession(): string
    {
        $result = [];
        $result['error'] = 1;

        $checkResult = $this->checkAuthorizedUseCase->execute();

        if ($checkResult === false) {
            $result['message'] = "Session not found";
            return json_encode(['result' => $result]);
        }

        $result['message'] = "Session found";
        $result['error'] = 0;
        return json_encode(['result' => $result]);
    }
}