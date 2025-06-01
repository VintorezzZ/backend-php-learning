<?php

class SaveBooksUseCase
{
    private IBookRepository $bookRepository;

    public function __construct(IBookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function execute(array $books): void
    {
        $this->bookRepository->saveAll($books);
    }
}