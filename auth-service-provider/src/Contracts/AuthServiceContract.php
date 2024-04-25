<?php

namespace Testtask\AuthServiceProvider\Contracts;

interface AuthServiceContract
{
    /**
     * Get user by token
     * @param  string  $token
     * @return array
     */
    public function getByToken(string $token): array;

    /**
     * Get single user by id
     * @param  string  $id
     * @return array|null
     */
    public function getById(string $id): ?array;

    /**
     * Get single user by email
     * @param  string  $email
     * @return array|null
     */
    public function getByEmail(string $email): ?array;

    /**
     * Get users by ids
     * @param  string[]  $ids
     * @return array
     */
    public function getByIds(array $ids): array;

    /**
     * Register user
     * @param  string|null  $name
     * @param  string|null  $nickname
     * @param  string|null  $email
     * @param  string  $password
     * @param  string|null  $provider
     * @param  string|null  $providerId
     * @param  string|null  $promoCode
     * @return void
     */
    public function register(
        ?string $first_name,
        ?string $last_name,
        ?string $email,
        string $password,
        ?string $provider = null,
        ?string $providerId = null,
        ?string $promoCode = null
    ): void;

    /**
     * Get impersonated token
     * @param  int  $userId
     * @return string
     */
    public function impersonate(int $userId): string;

    /**
     * Update user
     * @param  UserContract  $user
     * @return bool
     */
    public function updateUser(UserContract $user): bool;
}
