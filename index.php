<?php

use App\Cache;

require "vendor/autoload.php";


if (isset($_SERVER['If-None-Match'])) {
    $etag = $_SERVER['If-None-Match'];

    if (Cache::has($etag)) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
}

$requested = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $requested);
$className = "\\App\\Controllers\\" . ucwords($segments[0]);

return call_user_func([$className, $segments[1]]);

