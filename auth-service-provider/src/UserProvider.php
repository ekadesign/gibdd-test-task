<?php

namespace Testtask\AuthServiceProvider;

use Testtask\AuthServiceProvider\Contracts\UserContract;
use Testtask\AuthServiceProvider\Exceptions\AuthFailedException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;

class UserProvider implements BaseUserProvider
{
    public function __construct(
        protected UserContract $model
    ) {
    }

    public function retrieveById($identifier)
    {
        return $this->model->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        return null;
    }

    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        try {
            return $this->model->fetchUserByCredentials($credentials);
        } catch (AuthFailedException) {
            return null;
        }
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        return true;
    }
}
