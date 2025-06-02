<?php

namespace VintorezzZ\BackendPhpLearning\Domain\Book\Repository;

use VintorezzZ\BackendPhpLearning\Domain\Book\Entity\Book;

interface IBookRepository
{
    public function getAll(): array; // нужно жестко типизировать
    public function save(Book $book): bool;
    public function saveAll(array $books): bool;
}