<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\MySql\UserProfile;

use PDO;
use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Entity\UserProfile;
use VintorezzZ\BackendPhpLearning\Domain\UserProfile\Repository\IUserProfileRepository;
use VintorezzZ\BackendPhpLearning\Infrastructure\MySql\BaseMySqlRepository;

class MySqlUserProfileRepository extends BaseMySqlRepository implements IUserProfileRepository
{
    public function createProfile(int $userId, string $username, string $email): bool
    {
        $pdo = $this->getConnection();
        $this->createProfilesTableIfNotExists($pdo);

        $sql = 'INSERT INTO profiles (user_id, username, email) VALUES (?, ?, ?)';
        $query = $pdo->prepare($sql);

        if (!$query->execute([$userId, $username, $email])) {
            return false;
        }

        return true;
    }

    public function getProfile(int $userId): ?UserProfile
    {
        $pdo = $this->getConnection();
        $this->createProfilesTableIfNotExists($pdo);

        $sql = 'SELECT username, email FROM profiles WHERE user_id = :user_id';
        $query = $pdo->prepare($sql);
        $query->execute(['user_id' => $userId]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new UserProfile($result['username'], $result['email']);
    }

    public function updateUsername(int $userId, string $username): bool
    {
        $pdo = $this->getConnection();

        $sql = 'UPDATE profiles SET username = :username WHERE user_id = :user_id;';
        $query = $pdo->prepare($sql);

        if (!$query->execute(['username' => $username, 'user_id' => $userId])) {
            return false;
        }

        return true;
    }

    public function updateEmail(int $userId, string $email): bool
    {
        $pdo = $this->getConnection();

        $sql = 'UPDATE profiles SET email = :email WHERE user_id = :userId;';
        $query = $pdo->prepare($sql);

        if (!$query->execute(['email' => $email, 'userId' => $userId])) {
            return false;
        }

        return true;
    }

    private function createProfilesTableIfNotExists(PDO $pdo): void
    {
        $showSql = "SHOW TABLES LIKE 'profiles'";
        $result = $pdo->query($showSql);

        if ($result && $result->rowCount() === 0) {
            $createTableSql = "
            CREATE TABLE profiles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL UNIQUE,
                username VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ";
            $pdo->exec($createTableSql);
        }
    }
}