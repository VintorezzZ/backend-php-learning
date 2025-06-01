<?php

use Book;

interface IBookRepository
{
    public function getAll(): array; // нужно жестко типизировать
    public function save(Book $book): void;
    public function saveAll(array $books): void;
}