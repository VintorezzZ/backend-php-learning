<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\MySql\User;

use PDO;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\BaseMySqlRepository;

class MySqlUserRepository extends BaseMySqlRepository implements IUserRepository
{
    public function createAccessToken(string $token, int $userId): void
    {
        $pdo = $this->getConnection();

        $sql = 'INSERT INTO access_tokens (token, expired, user_id) VALUES (?, ?, ?)';
        $query = $pdo->prepare($sql);
        $query->execute([$token, 0, $userId]);
    }

    public function get(string $username): ?User
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = 'SELECT id, uId, username, email, password FROM users WHERE username = :username';
        $query = $pdo->prepare($sql);
        $query->execute(['username' => $username]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new User($result['id'], $result['uId'], $result['username'], $result['email'], $result['password']);
    }

    public function add(string $uId, string $email, string $username, string $password): bool
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = 'INSERT INTO users (uId, email, username, password) VALUES (?, ?, ?, ?)';
        $query = $pdo->prepare($sql);

        if (!$query->execute([$uId, $email, $username, $password])) {
            return false;
        }

        return true;
    }

    public function delete(User $user): bool
    {
        // TODO: Implement delete() method.
    }

    public function update(User $user): bool
    {
        // TODO: Implement update() method.
    }

    public function exists(string $email): ?User
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = "SELECT id, uId, username, email FROM users WHERE email = :email";
        $query = $pdo->prepare($sql);
        $query->execute(['email' => $email]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new User($result['id'], $result['uId'], $result['username'], $result['email']);
    }

    private function createUsersTableIfNotExists(PDO $pdo): void
    {
        $showSql = "SHOW TABLES LIKE 'users'";
        $result = $pdo->query($showSql);

        if ($result && $result->rowCount() === 0) {
            $createTableSql = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                uId VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";

            $pdo->exec($createTableSql);
        }
    }
}