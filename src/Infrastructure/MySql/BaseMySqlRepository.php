<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\MySql;

use PDO;
use PDOException;

abstract class BaseMySqlRepository
{
    protected string $host = 'localhost';
    protected string $dbname = 'php-website';
    protected string $user = 'root';
    protected string $pass = 'root';
    protected string $port = '3306';

    protected function getConnection(): PDO
    {
        try {
            $pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=utf8", $this->user, $this->pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Ошибка подключения к базе: " . $e->getMessage());
        }

        return $pdo;
    }
}