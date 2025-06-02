<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Book\GetBooksListUseCase;

class GetBooksController
{
    protected GetBooksListUseCase $getBooksUseCase;

    public function __construct(GetBooksListUseCase $useCase)
    {
        $this->getBooksUseCase = $useCase;
    }

    public function create(): array
    {
        return $this->getBooksUseCase->execute();
    }
}