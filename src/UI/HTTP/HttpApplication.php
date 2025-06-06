<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthorizeUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\CheckAuthorizedUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\LogoutUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\RegisterUseCase;
use VintorezzZ\BackendPhpLearning\Application\Book\GetBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Application\Book\SaveBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\CheckUserExistsUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\DeleteUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\GetUserUseCase;
use VintorezzZ\BackendPhpLearning\Domain\Auth\Entity\Authorization;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\Book\MySqlBookRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\User\MySqlUserRepository;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers\GetBooksController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers\SaveBooksController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\DTO\BookDTOFactory;
use VintorezzZ\BackendPhpLearning\UI\HTTP\User\Controllers\AuthController;

//header('Content-Type: application/json');
session_start();

class HttpApplication
{
    //CORS разрешения
    private array $allowedOrigins = [
        'example.com',
        'app.example.com',
        'localhost',
        '127.0.0.1',
    ];

    public function runRequest(string $input, array $server): string
    {
        $request = new Request($_SERVER['REQUEST_METHOD'], getallheaders(), $input, $_GET);

        // Проверяем, является ли отправитель запроса валидным
        if (isset($server['HTTP_ORIGIN'])) {
            $originHeader = $server['HTTP_ORIGIN'];
            $parsedUrl = parse_url($originHeader);
            $host = $parsedUrl['host'] ?? '';

            if (in_array($host, $this->allowedOrigins)) {
                header("Access-Control-Allow-Origin: $originHeader");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization');

                if ($server['REQUEST_METHOD'] === 'OPTIONS') {
                    return '';
                }
            }
        }

        // Определяем путь запроса (если нужно)
        $path = $server['REQUEST_URI'] ?? '/';

        // Например, если URL заканчивается на /getBooks — вернуть книги
        // Можно добавить проверку пути, если необходимо
        if (strpos($path, '/getBooks')) {
            $mySqlBookRepository = new MySqlBookRepository;
            $getBookListUseCase = new GetBooksListUseCase($mySqlBookRepository);
            $bookDTOFactory = new BookDTOFactory();
            $getBooksController = new GetBooksController($getBookListUseCase, $bookDTOFactory);
            $books = $getBooksController->create();
            return json_encode($books);
        }

        if (strpos($path, '/saveBooks')) {
            $mySqlBookRepository = new MySqlBookRepository;
            $saveBooksUseCase = new SaveBooksListUseCase($mySqlBookRepository);
            $saveBooksController = new SaveBooksController($saveBooksUseCase);
            return $saveBooksController->create($request);
        }

        if (str_contains($path, 'auth/login')) {
            $authController = $this->createAuthUserController();
            return $authController->login($request);
        }

        if (str_contains($path, 'auth/register')) {
            $authController = $this->createAuthUserController();
            return $authController->register($request);
        }

        if (str_contains($path, 'auth/logout')) {
            $authController = $this->createAuthUserController();
            return $authController->logout($request);
        }

        if (str_contains($path, 'auth/delete')) {
            $authController = $this->createAuthUserController();
            return $authController->deleteUser($request);
        }

        if (str_contains($path, 'auth/checkSession')) {
            $authController = $this->createAuthUserController();
            return $authController->checkSession($request);
        }

        // Если ни одно условие не сработало — вернуть ошибку
        http_response_code(404);
        return json_encode([
            'error' => 1,
            'message' => "Маршрут не найден: $path"
        ]);
    }

    private function createAuthUserController(): AuthController
    {
        $mySqlUserRepository = new MySqlUserRepository();
        $getUserUseCase = new GetUserUseCase($mySqlUserRepository);
        $deleteUserUseCase = new DeleteUserUseCase($mySqlUserRepository);
        $checkUserExistsUseCase = new CheckUserExistsUseCase($mySqlUserRepository);
        $registerUseCase = new RegisterUseCase($mySqlUserRepository);
        $authorizeUserUseCase = new AuthorizeUseCase($mySqlUserRepository);
        $checkAuthorizedUseCase = new CheckAuthorizedUseCase($mySqlUserRepository);
        $logoutUserUseCase = new LogoutUseCase($mySqlUserRepository);
        $authorization = new Authorization
        (
            $getUserUseCase,
            $deleteUserUseCase,
            $checkUserExistsUseCase,
            $registerUseCase,
            $authorizeUserUseCase,
            $checkAuthorizedUseCase,
            $logoutUserUseCase
        );
        return new AuthController($authorization);
    }
}