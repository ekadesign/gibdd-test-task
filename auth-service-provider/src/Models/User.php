<?php

namespace Testtask\AuthServiceProvider\Models;

use Illuminate\Support\Arr;
use Testtask\AuthServiceProvider\Contracts\AuthServiceContract;
use Testtask\AuthServiceProvider\Contracts\UserContract;

class User implements UserContract
{
    protected array $changes = [];

    public function __construct(
        protected array $attributes = []
    ) {

    }

    public function getKey(): int
    {
        return $this->{$this->getKeyName()};
    }

    public function getKeyName(): string
    {
        return 'id';
    }

    public function find(int $id): UserContract|null
    {
        $this->attributes = app(AuthServiceContract::class)->getById($id);
        return $this;
    }

    public function findByIds(array $ids): array
    {
        return array_map(
            function ($data) {
                return new static($data);
            },
            app(AuthServiceContract::class)->getByIds($ids)
        );
    }

    public function findByEmail(string $email): UserContract|null
    {
        $data = app(AuthServiceContract::class)->getByEmail($email);
        if ($data) {
            $this->attributes = $data;
            return $this;
        } else {
            return null;
        }
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function save(): bool
    {
        if (empty($this->changes)) {
            return true;
        }
        if (app(AuthServiceContract::class)->updateUser($this)) {
            $this->changes = [];
            return true;
        }
        return false;
    }

    public function update($data): bool
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this->save();
    }

    public function fetchUserByCredentials(array $credentials): self
    {
        $this->attributes = app(AuthServiceContract::class)->getByToken($credentials['api_token']);
        return $this;
    }

    public function getAuthIdentifierName(): string
    {
        return $this->getKeyName();
    }

    public function getAuthIdentifier(): string
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function getAuthPassword(): string
    {
        return '';
    }

    public function getRememberToken(): string
    {
        return '';
    }

    public function setRememberToken($value): void
    {
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value)
    {
        if ($this->attributes[$name] != $value && !in_array($name, $this->changes)) {
            $this->changes[] = $name;
        }
        $this->attributes[$name] = $value;
    }

    public function getRouteKey()
    {
        return $this->attributes[$this->getRouteKeyName()];
    }

    public function getRouteKeyName(): string
    {
        return $this->getKeyName();
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return $this->find($value);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->find($value);
    }

}
