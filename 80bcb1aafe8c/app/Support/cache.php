<?php

use Illuminate\Support\Facades\Cache;

function cache_get(Closure $callback, string $key, int $ttl = 300): mixed
{
    return Cache::tags(explode(':', $key))->remember($key, $ttl, $callback);
}
