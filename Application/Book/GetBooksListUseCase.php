<?php

use IBookRepository;

class GetBooksListUseCase
{
    private IBookRepository $bookRepository;

    public function __construct(IBookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(): array
    {
        return $this->bookRepository->getAll();
    }
}