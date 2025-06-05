<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Book\SaveBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Infrastructure\HTTP\Request;

class SaveBooksController
{
    protected SaveBooksListUseCase $saveBooksUseCase;

    public function __construct(SaveBooksListUseCase $useCase)
    {
        $this->saveBooksUseCase = $useCase;
    }

    public function create(Request $request): string
    {
        $input = json_decode($request->content, true);

        if (!isset($input['books']) || !is_array($input['books'])) {
            return json_encode([
                'status' => 'error',
                'message' => 'Некорректные данные']);
        }

        $books = $input['books'];
        $result = $this->saveBooksUseCase->execute($books);

        if ($result === true) {
            return json_encode([
                'status' => 'success',
                'message' => "Успешно сохранено"
            ]);
        } else {
            return json_encode([
                'status' => 'error',
                'message' => "Ошибка сохранения"
            ]);
        }
    }
}