<?php

namespace VintorezzZ\BackendPhpLearning\Application\User;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

class AuthorizeUserUseCase
{
    private IUserRepository $userRepository;

    function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authorize(User $user): string
    {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
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
        }

        $bytes = random_bytes(5);
        $token = bin2hex($bytes);
        $this->userRepository->createAccessToken($token, $user->id);
        return $token;
    }
}