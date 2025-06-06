<?php

namespace VintorezzZ\BackendPhpLearning\Application\Auth;

use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class LogoutUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): bool
    {
        //$this->userRepository->deleteAccessToken($sessionToken);

        $_SESSION = array();
        session_destroy();

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = $_SERVER['HTTP_ORIGIN'];

            // Устанавливаем куки с доменом, с которого пришел запрос
            $parsedUrl = parse_url($origin);
            $clientDomain = $parsedUrl['host']; // Получаем хост из URL

            setcookie("PHPSESSID", '', [
                'expires' => time() - 3600, // Жизненный цикл куки
                'path' => '/', // Путь, на который будет доступна кука
                'domain' => $clientDomain, // Указание домена, если это необходимо (localhost для локальной разработки)
                'secure' => false, // Установить true, если приложение работает по HTTPS
                'httponly' => true, // Защита куки от доступа через JavaScript
                'samesite' => 'Lax', // Политика SameSite (можно использовать 'strict' или 'none')
            ]);
        }

        return true;
    }
}