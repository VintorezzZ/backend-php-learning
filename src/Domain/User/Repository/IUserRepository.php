<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Repository;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;

interface IUserRepository
{
    public function get(string $login): ?User;
    public function add(string $login, string $email, string $password): bool;
    public function delete(User $user): bool;
    public function update(User $user): bool;
    public function exists(string $login): ?User;
    public function createAccessToken(string $token, int $userId): void;
    public function deleteAccessToken(string $token): void;

}