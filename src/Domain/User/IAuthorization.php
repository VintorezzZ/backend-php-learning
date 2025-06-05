<?php

namespace VintorezzZ\BackendPhpLearning\Domain\User;

use VintorezzZ\BackendPhpLearning\Application\User\AuthorizeUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\GetUserUseCase;
use VintorezzZ\BackendPhpLearning\Application\User\RegisterUserUseCase;
use VintorezzZ\BackendPhpLearning\Domain\User\Entity\User;
use VintorezzZ\BackendPhpLearning\Domain\User\Repository\IUserRepository;

interface IAuthorization
{
    public function __construct(IUserRepository $userRepository, GetUserUseCase $getUserUseCase, RegisterUserUseCase $saveUserUseCase, AuthorizeUserUseCase $authorizeUserUseCase);
    public function login(string $username, string $password): array;
    public function logout(string $sessionId): bool;
    public function register(string $email, string $username, string $password): array;
    public function getUser(int $username): User;
    public function deleteUser(int $username, string $password): bool;
}