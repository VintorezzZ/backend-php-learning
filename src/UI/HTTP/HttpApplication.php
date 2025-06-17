<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP;

use VintorezzZ\BackendPhpLearning\Application\Auth\AuthAuthorizeUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthCheckAuthorizedUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthLogoutUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\AuthRegisterUseCase;
use VintorezzZ\BackendPhpLearning\Application\Auth\UserDataValidator;
use VintorezzZ\BackendPhpLearning\Application\Book\GetBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Application\Book\SaveBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserDeleteUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserExistsCheckUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserGetByLoginUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileCreateUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileGetByUserIdUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileUpdateEmailUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\UserProfile\UserProfileUpdateUsernameUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\Book\MySqlBookRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\User\MySqlUserRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\User\UserProfile\MySqlUserProfileRepository;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers\AuthAuthorizeController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers\AuthCheckAuthorizedController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers\AuthDeleteUserController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers\AuthLogoutController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Auth\Controllers\AuthRegisterController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers\GetBooksController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers\SaveBooksController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\DTO\BookDTOFactory;
use VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers\UserProfileCreateController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers\UserProfileGetByUserIdController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers\UserProfileUpdateEmailController;
use VintorezzZ\BackendPhpLearning\UI\HTTP\User\UserProfile\Controllers\UserProfileUpdateUsernameController;

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
        //header("Access-Control-Allow-Origin: *");
        if (isset($server['HTTP_ORIGIN'])) {
            $originHeader = $server['HTTP_ORIGIN'];
            $parsedUrl = parse_url($originHeader);
            $host = $parsedUrl['host'] ?? '';

            if (in_array($host, $this->allowedOrigins)) {
                header("Access-Control-Allow-Origin: $originHeader");
                header('Access-Control-Allow-Credentials: true');
                header('Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE');
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
            return $saveBooksController->save($request);
        }

        if (str_contains($path, 'auth/login')) {
            $mySqlUserRepository = new MySqlUserRepository();
            $userGetByLoginUseCase = new UserGetByLoginUseCase($mySqlUserRepository);
            $authorizeUseCase = new AuthAuthorizeUseCase();
            $authAuthorizeController = new AuthAuthorizeController($authorizeUseCase, $userGetByLoginUseCase);
            return $authAuthorizeController->login($request);
        }

        if (str_contains($path, 'auth/register')) {
            $mySqlUserRepository = new MySqlUserRepository();
            $getUserByLoginUseCase = new UserGetByLoginUseCase($mySqlUserRepository);
            $checkUserExistsUseCase = new UserExistsCheckUseCase($mySqlUserRepository);
            $registerUseCase = new AuthRegisterUseCase($mySqlUserRepository);
            $authorizeUseCase = new AuthAuthorizeUseCase();
            $registerUserController = new AuthRegisterController($registerUseCase, $checkUserExistsUseCase, $getUserByLoginUseCase, $authorizeUseCase);

            $registerProfileDataValidationResult = UserDataValidator::validateRegisterProfileData($request);

            if ($registerProfileDataValidationResult['error'] !== 0)
                return json_encode(['result' => $registerProfileDataValidationResult]);

            $registerResult = $registerUserController->register($request);

            if ($registerResult['error'] !== 0)
                return json_encode(['result' => $registerResult]);

            $mysSqlUserProfileRepository = new MySqlUserProfileRepository;
            $userProfileCreateUseCase = new UserProfileCreateUseCase($mysSqlUserProfileRepository);
            $userProfileCreateController = new UserProfileCreateController($userProfileCreateUseCase);
            return $userProfileCreateController->createUserProfile($registerResult['userId'], $request);
        }

        if (str_contains($path, 'auth/logout')) {
            $logoutUseCase = new AuthLogoutUseCase();
            $logoutController = new AuthLogoutController($logoutUseCase);
            return $logoutController->logout();
        }

        if (str_contains($path, 'auth/delete')) {
            $mySqlUserRepository = new MySqlUserRepository();
            $authLogoutUseCase = new AuthLogoutUseCase();
            $userGetByLoginUseCase = new UserGetByLoginUseCase($mySqlUserRepository);
            $userDeleteUseCase = new UserDeleteUseCase($mySqlUserRepository);
            $deleteUserController = new AuthDeleteUserController($userDeleteUseCase, $userGetByLoginUseCase, $authLogoutUseCase);
            return $deleteUserController->deleteUser();
        }

        if (str_contains($path, 'auth/checkSession')) {
            $authCheckAuthorizedUseCase = new AuthCheckAuthorizedUseCase();
            $authCheckAuthorizedController = new AuthCheckAuthorizedController($authCheckAuthorizedUseCase);
            return $authCheckAuthorizedController->checkSession();
        }

        if (str_contains($path, 'profile/getProfile')) {
            $mysSqlUserProfileRepository = new MySqlUserProfileRepository;
            $userProfileGetByUserIdUseCase = new UserProfileGetByUserIdUseCase($mysSqlUserProfileRepository);
            $userProfileGetByUserIdController = new UserProfileGetByUserIdController($userProfileGetByUserIdUseCase);
            return $userProfileGetByUserIdController->getUserProfile();
        }

        if (str_contains($path, 'profile/updateUsername')) {
            $mysSqlUserProfileRepository = new MySqlUserProfileRepository;
            $userProfileUpdateUsernameUseCase = new UserProfileUpdateUsernameUseCase($mysSqlUserProfileRepository);
            $userProfileUpdateUsernameController = new UserProfileUpdateUsernameController($userProfileUpdateUsernameUseCase);
            return $userProfileUpdateUsernameController->updateProfileUsername($request);
        }

        if (str_contains($path, 'profile/updateEmail')) {
            $mysSqlUserProfileRepository = new MySqlUserProfileRepository;
            $userProfileUpdateEmailUseCase = new UserProfileUpdateEmailUseCase($mysSqlUserProfileRepository);
            $userProfileUpdateEmailController = new UserProfileUpdateEmailController($userProfileUpdateEmailUseCase);
            return $userProfileUpdateEmailController->updateProfileEmail($request);
        }

        // Если ни одно условие не сработало — вернуть ошибку
        http_response_code(404);

        $response = [];
        $response['error'] = 1;
        $response['message'] = "Route Not Found: $path";

        return json_encode(['result' => $response]);
    }
}