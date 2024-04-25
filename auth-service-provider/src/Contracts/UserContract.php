<?php

namespace Testtask\AuthServiceProvider\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

interface UserContract extends Authenticatable, Arrayable, Jsonable, JsonSerializable, UrlRoutable
{
    /**
     * Get model key
     * @return int
     */
    public function getKey(): int;

    /**
     * Get model key name
     * @return string
     */
    public function getKeyName(): string;

    /**
     * Get user by id
     * @param  int  $id
     * @return static|null
     */
    public function find(int $id): UserContract|null;

    /**
     * Get users by ids
     * @param  array|static[]  $ids
     * @return array
     */
    public function findByIds(array $ids): array;

    /**
     * Get user by email
     * @param string $email
     * @return array
     */
    public function findByEmail(string $email): UserContract|null;

    /**
     * Return changed attributes
     * @return array
     */
    public function getChanges(): array;

    /**
     * Save user
     * @return bool
     */
    public function save(): bool;

    /**
     * Update user
     * @param array $data
     * @return bool
     */
    public function update($data): bool;

    /**
     * Get user by credentials
     * @param  array  $credentials
     * @return $this
     */
    public function fetchUserByCredentials(array $credentials): self;

}
