<?php

namespace VintorezzZ\BackendPhpLearning\Domain\Book\Entity;

class Book
{
    public readonly string $title;
    public readonly string $author;
    public readonly string $id;

    public function __construct(string $title, string $author, string $id)
    {
        $this->title = $title;
        $this->author = $author;
        $this->id = $id;
    }

    // тут быть не должно.
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
        ];
    }
}
