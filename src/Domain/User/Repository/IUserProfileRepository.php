<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User\Repository;

use VintorezzZ\BackendPhpLearning\Domain\User\ValueObject\UserProfile;

interface IUserProfileRepository
{
    public function createProfile(int $userId, string $username, string $email): bool;
    public function getProfile(int $userId): ?UserProfile;
    public function updateUsername(int $userId, string $username): bool;
    public function updateEmail(int $userId, string $email): bool;
}