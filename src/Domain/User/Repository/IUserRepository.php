<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Repository;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;

interface IUserRepository
{
    public function getUserByLogin(string $login): ?User;
    public function addUser(string $login, string $password): bool;
    public function deleteUser(int $id): bool;
    public function existsUser(string $login): ?User;
    public function createAccessToken(string $token, int $userId): void;
    public function deleteAccessToken(string $token): void;

}