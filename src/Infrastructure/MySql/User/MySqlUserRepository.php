<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\MySql\User;

use PDO;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\BaseMySqlRepository;

class MySqlUserRepository extends BaseMySqlRepository implements IUserRepository
{
    public function get(string $login): ?User
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = 'SELECT id, login, email, password FROM users WHERE login = :login';
        $query = $pdo->prepare($sql);
        $query->execute(['login' => $login]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new User($result['id'], $result['login'], $result['email'], $result['password']);
    }

    public function add(string $login, string $email, string $password): bool
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = 'INSERT INTO users (login, email, password) VALUES (?, ?, ?)';
        $query = $pdo->prepare($sql);

        if (!$query->execute([$login, $email, $password])) {
            return false;
        }

        return true;
    }

    public function delete(User $user): bool
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = 'DELETE FROM users WHERE login = :login';
        $query = $pdo->prepare($sql);

        if (!$query->execute(['login' => $user->login])) {
            return false;
        }

        return true;
    }

    public function update(User $user): bool
    {
        // TODO: Implement update() method.
    }

    public function exists(string $login): ?User
    {
        $pdo = $this->getConnection();
        $this->createUsersTableIfNotExists($pdo);

        $sql = "SELECT id, login, email, password FROM users WHERE login = :login";
        $query = $pdo->prepare($sql);
        $query->execute(['login' => $login]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new User($result['id'], $result['login'], $result['email'], $result['password']);
    }

    public function createAccessToken(string $token, int $userId): void
    {
        $pdo = $this->getConnection();

        $sql = 'INSERT INTO access_tokens (token, expired, user_id) VALUES (?, ?, ?)';
        $query = $pdo->prepare($sql);
        $query->execute([$token, 0, $userId]);
    }

    public function deleteAccessToken(string $token): void
    {
        $pdo = $this->getConnection();

        $sql = 'DELETE FROM access_tokens WHERE token = :token';
        $query = $pdo->prepare($sql);
        $query->execute([$token]);
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