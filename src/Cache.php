<?php

namespace App;

use Predis\Autoloader;
use Predis\Client;

class Cache implements CacheContract
{

    const EXPIRATION_TIME = 3600;

    /**
     * @var Client
     */
    private static Client $driver;

    public function __construct()
    {
        Autoloader::register();

        self::$driver = $this->connect();
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set($key, $value)
    {
        // TODO: Implement set() method.

        return self::$driver->set($key, $value, time() + self::EXPIRATION_TIME);
    }

    /**
     * @param $key
     * @return false|string|null
     */
    public static function get($key)
    {
        // TODO: Implement get() method.

        return self::has($key) ? self::$driver->get($key) : false;
    }

    /**
     * @param $key
     * @return int
     */
    public static function has(...$key): int
    {
        // TODO: Implement has() method.
        return self::$driver->exists(...$key);
    }

    /**
     * @param $key
     * @return false
     */
    public static function destroy($key)
    {
        if (self::has($key))
            return false;

        return self::$driver->del($key);
    }

    /**
     * Establish Redis connection
     */
    public function connect(): Client
    {
        try {
            // This connection is for a remote server
            return new Client(array(
                "scheme" => "tcp",
                "host" => "127.0.01",
                "port" => 6379
            ));

        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
