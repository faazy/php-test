<?php

namespace App\Controllers;

use App\Cache;
use App\Response;
use SimpleJsonRequest;

class PostsController
{
    const API_BASE_URL = 'https://jsonplaceholder.typicode.com/posts';

    /**
     * Get Post List
     *
     * @return false|mixed|string|null
     */
    public function index()
    {
        if ($posts = Cache::get('posts')) {
            return $posts;
        }

        $posts = SimpleJsonRequest::get(self::API_BASE_URL);

        Cache::set($this->generateEtag('list'), $posts);

        return Response::json($posts);
    }

    function store()
    {
        $post = SimpleJsonRequest::post(self::API_BASE_URL, null, $_POST);

        if ($post) {
            $this->forgetProductCache();
        }

        return Response::json($post);
    }


    /**
     * @return false|mixed
     */
    public function show()
    {
        if (!isset($_GET['id'])) {
            return false;
        }

        $post = Cache::get('post/' . $_GET['id']);

        if (!$post) {
            $post = SimpleJsonRequest::get(self::API_BASE_URL . $_GET['id']);
            Cache::set($this->generateEtag($_GET['id']), $post);
        }

        return Response::json($post);
    }

    /**
     * @return false|mixed
     */
    public function update()
    {
        if (!isset($_POST['id'])) {
            return false;
        }

        $post = SimpleJsonRequest::put(self::API_BASE_URL . $_POST['id'], null, $_POST);

        if ($post) {
            $this->forgetProductCache();
        }

        return Response::json($post);
    }

    /**
     * @return false|mixed
     */
    public function destroy()
    {
        $request = new SimpleJsonRequest();

        if (!isset($_REQUEST['id'])) {
            return false;
        }

        $post = $request->delete(self::API_BASE_URL . $_REQUEST['id']);

        if ($post) {
            $this->forgetProductCache();
        }

        return Response::json($post);
    }


    /**
     * Perform unlink the cache
     *
     * @return false
     */
    public function forgetProductCache()
    {
        return Cache::destroy('posts');
    }

    /**
     * @param $key
     * @return string
     */
    private function generateEtag($key): string
    {
        return hash('sha256', sprintf('posts-%s', $key));
    }

}
