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

    public function save(Request $request): string
    {
        $input = json_decode($request->content, true);

        if (!isset($input['data']) || !is_array($input['data'])) {
            return json_encode([
                'status' => 'error',
                'message' => 'Некорректные данные']);
        }

        $books = $input['data'];
        $resultSuccess = $this->saveBooksUseCase->execute($books);

        $result = [];
        $result['error'] = $resultSuccess ? 0 : 1;
        $result['message'] = $resultSuccess ? "Успешно сохранено" : "Ошибка сохранения";

        return json_encode(['result' => $result]);
    }
}