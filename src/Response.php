<?php


namespace App;


class Response
{

    /**
     * @param $data
     * @param null $etag
     * @return false|string
     */
    public static function json($data, $etag = null)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json');

        if ($etag)
            header('ETag:' . $etag);

        return json_encode($data);
    }
}
