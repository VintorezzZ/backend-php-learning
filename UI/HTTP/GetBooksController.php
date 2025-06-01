<?php

class GetBooksController
{
    protected $getBooksUseCase;

    public function __construct(GetBooksListUseCase $useCase)
    {
        $this->getBooksUseCase = $useCase;
    }

    public function create()
    {
        $result = $this->getBooksUseCase->execute();
        echo $result;
    }
}