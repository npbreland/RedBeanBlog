<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

require_once 'bootstrap.php';

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;

use RedBeanBlog\Exception\Exception400;
use RedBeanBlog\Exception\Exception404;

$router = new RouteCollector();

$c_ns = '\RedBeanBlog\Controller\\';

$router->get('/posts', [$c_ns . 'Posts', 'readAll']);
$router->get('/posts/{id:i}', [$c_ns . 'Posts', 'read']);
$router->post('/posts', [$c_ns . 'Posts', 'create']);
$router->put('/posts/{id:i}', [$c_ns . 'Posts', 'update']);
$router->delete('/posts/', [$c_ns . 'Posts', 'delete']);


$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$dispatcher = new Dispatcher($router->getData());

try {
    $dispatcher->dispatch($method, parse_url($uri, PHP_URL_PATH));
} catch (Exception400 $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 400 ' . $e->getMessage());
} catch (Exception404 $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 ' . $e->getMessage());
} catch (\Exception $e) {
    // Fallback to 500 error if we can't explain
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
}
