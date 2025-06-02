<?php

namespace VintorezzZ\BackendPhpLearning\Application\Book;

use VintorezzZ\BackendPhpLearning\Domain\Book\Repository\IBookRepository;

class SaveBooksListUseCase
{
    private IBookRepository $bookRepository;

    public function __construct(IBookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(array $books): bool
    {
        return $this->bookRepository->saveAll($books);
    }
}