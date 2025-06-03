<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Book\Controllers;

use VintorezzZ\BackendPhpLearning\Application\Book\GetBooksListUseCase;
use VintorezzZ\BackendPhpLearning\Domain\Book\Entity\Book;
use VintorezzZ\BackendPhpLearning\UI\HTTP\Book\DTO\BookDTOFactory;

class GetBooksController
{
    private GetBooksListUseCase $getBooksUseCase;
    private BookDTOFactory $bookDTOFactory;

    public function __construct(GetBooksListUseCase $useCase, BookDtoFactory $DTOFactory)
    {
        $this->getBooksUseCase = $useCase;
        $this->bookDTOFactory = $DTOFactory;
    }

    public function create(): array
    {
        return array_map(fn(Book $b) => $this->bookDTOFactory->createFromDomain($b), $this->getBooksUseCase->execute());
    }
}