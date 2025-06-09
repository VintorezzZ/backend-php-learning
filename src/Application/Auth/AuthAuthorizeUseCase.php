<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class AuthAuthorizeUseCase
{
    function __construct()
    {
    }

    public function execute(int $id, string $login, string $password): bool
    {
        /*        if (isset($_SERVER['HTTP_ORIGIN'])) {
                    $origin = $_SERVER['HTTP_ORIGIN'];

                    // Устанавливаем куки с доменом, с которого пришел запрос
                    $parsedUrl = parse_url($origin);
                    $clientDomain = $parsedUrl['host']; // Получаем хост из URL

                    setcookie("login", $user->name, [
                        'expires' => 0, // Жизненный цикл куки
                        'path' => '/', // Путь, на который будет доступна кука
                        'domain' => $clientDomain, // Указание домена, если это необходимо (localhost для локальной разработки)
                        'secure' => false, // Установить true, если приложение работает по HTTPS
                        'httponly' => true, // Защита куки от доступа через JavaScript
                        'samesite' => 'Lax', // Политика SameSite (можно использовать 'strict' или 'none')
                    ]);
                }*/

        /*$bytes = random_bytes(5);
        $token = bin2hex($bytes);
        $this->userRepository->createAccessToken($token, $id);*/

        $_SESSION['userId'] = $id;
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        //$_SESSION['token'] = $token;

        return true;
    }
}