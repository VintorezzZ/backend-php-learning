<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Repository;

use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;

interface IUserRepository
{
    public function get(string $username): ?User;
    public function add(string $uId, string $email, string $username, string $password): bool;
    public function delete(User $user): bool;
    public function update(User $user): bool;
    public function exists(string $email): ?User;
    public function createAccessToken(string $token, int $userId): void;

}