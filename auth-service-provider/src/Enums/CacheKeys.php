<?php

namespace Testtask\AuthServiceProvider\Enums;

enum CacheKeys: string
{
    case AUTH_TOKEN = 'token:%s';
}
