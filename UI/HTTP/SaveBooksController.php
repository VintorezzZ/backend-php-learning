<?php

class SaveBooksController
{
    protected $saveBooksUseCase;

    public function __construct(SaveBooksUseCase $useCase)
    {
        $this->saveBooksUseCase = $useCase;
    }

    public function create(string $inputJSON)
    {
        $input = json_decode($inputJSON, true);

        if (!isset($input['books']) || !is_array($input['books'])) {
            echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
            exit;
        }

        $books = $input['books'];
        $result = $this->saveBooksUseCase->execute($books);

        echo $result;
    }
}