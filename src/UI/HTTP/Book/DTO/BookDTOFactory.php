<?php

namespace VintorezzZ\BackendPhpLearning\UI\HTTP\Book\DTO;

use VintorezzZ\BackendPhpLearning\Domain\Book\Entity\Book;

class BookDTOFactory
{
    public function createFromDomain(Book $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
        ];
    }
}