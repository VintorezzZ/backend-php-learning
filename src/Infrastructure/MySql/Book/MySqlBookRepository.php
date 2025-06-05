<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\MySql\Book;

use PDO;
use PDOException;
use VintorezzZ\BackendPhpLearning\Domain\Book\Repository\IBookRepository;
use VintorezzZ\BackendPhpLearning\Domain\Book\Entity\Book;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\BaseMySqlRepository;

class MySqlBookRepository extends BaseMySqlRepository implements IBookRepository
{
    public function getAll(): array
    {
        $pdo = $this->getConnection();
        $this->createBooksTableIfNotExists($pdo);

        $sql = 'SELECT title, author, id FROM books';
        $result = $pdo->query($sql);
        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        $books = [];
        foreach ($rows as $row) {
            $books[] = new Book($row['title'], $row['author'], $row['id']);
        }

        return $books;
    }

    public function save(Book $book): bool
    {
        // TODO: Implement save() method.
        return false;
    }

    public function saveAll(array $books): bool
    {
        try {
            $pdo = $this->getConnection();
            $this->createBooksTableIfNotExists($pdo);

            $sql = 'INSERT INTO books (title, author) VALUES (?, ?)';
            $query = $pdo->prepare($sql);

            foreach ($books as $book) {
                if (!isset($book['title']) || !isset($book['author']))
                    continue;

                $title = trim($book['title']);
                $author = trim($book['author']);
                $query->execute([$title, $author]);
            }

            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Проверяет наличие таблицы 'books', если её нет — создаёт и наполняет данными из кэша.
     * @return void
     */
    private function createBooksTableIfNotExists(PDO $pdo): void
    {
        $showSql = "SHOW TABLES LIKE 'books'";
        $result = $pdo->query($showSql);

        if ($result && $result->rowCount() === 0) {
            $createTableSql = "
            CREATE TABLE books (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                author VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

            $pdo->exec($createTableSql);
        }
    }
}