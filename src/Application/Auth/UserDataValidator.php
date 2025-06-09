<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class UserDataValidator
{
    private const int USERNAME_CHARS_MIN_COUNT = 3;
    private const int USERNAME_CHARS_MAX_COUNT = 40;
    private const int PASSWORD_CHARS_MIN_COUNT = 5;
    private const int PASSWORD_CHARS_MAX_COUNT = 40;

    public static function validateCredentials(string $login, string $password): array
    {
        $result = [];
        $result['error'] = 1;

        $usernameValidationResult = self::validateLogin($login);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $passwordValidationResult = self::validatePassword($password);

        if ($passwordValidationResult['error']) {
            $result['message'] = $passwordValidationResult['message'];
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }

    public static function validateLogin(string $login): array
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

    public static function validatePassword(string $password): array
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

    public static function validateEmail(string $email): array
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

    public static function validateUsername(string $username): array
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

    public static function validateRegisterProfileData(Request $request): array
    {
        $result = [];
        $result['error'] = 1;

        $input = json_decode($request->content, true);
        $input = $input['data'] ?? null;

        $username = $input['username'];
        $email = $input['email'];

        $usernameValidationResult = self::validateUsername($username);

        if ($usernameValidationResult['error']) {
            $result['message'] = $usernameValidationResult['message'];
            return $result;
        }

        $emailValidationResult = self::validateEmail($email);

        if ($emailValidationResult['error']) {
            $result['message'] = $emailValidationResult['message'];
            return $result;
        }

        $result['error'] = 0;
        return $result;
    }
}