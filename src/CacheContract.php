<?php

namespace App;

interface CacheContract
{
    public static function set($key, $value);

    public static function get($key);

    public static function has(...$key);
}
