<?php

class HttpApplication
{
    //CORS разрешения
    private array $allowedOrigins = [
        'example.com',
        'app.example.com',
        'localhost',
        '127.0.0.1',
    ];

    private GetBooksController $getBooksController;

    public function runRequest(string $input, array $server): void
    {
        // Проверяем, является ли отправитель запроса валидным
        if (isset($server['HTTP_ORIGIN'])) {
            $originHeader = $server['HTTP_ORIGIN'];
            $parsedUrl = parse_url($originHeader);
            $host = $parsedUrl['host'] ?? '';

            if (in_array($host, $this->allowedOrigins)) {
                header("Access-Control-Allow-Origin: $originHeader");
                // header('Access-Control-Allow-Credentials: true');
                // header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
                // header('Access-Control-Allow-Headers: Content-Type, Authorization');

                if ($server['REQUEST_METHOD'] === 'OPTIONS') {
                    exit;
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
            $getBooksController = new GetBooksController($getBookListUseCase);
            $getBooksController->create();
            exit;
        }

        if (strpos($path, '/saveBooks')) {
            $mySqlBookRepository = new MySqlBookRepository;
            $saveBooksUseCase = new SaveBooksUseCase($mySqlBookRepository);
            $saveBooksController = new SaveBooksController($saveBooksUseCase);
            
            $saveBooksController->create($input);
            exit;
        }

        // Если ни одно условие не сработало — вернуть ошибку
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => "Маршрут не найден: $path"
        ]);
    }
}
