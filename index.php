<?php
require_once 'bootstrap.php';

define('CLIENT_500_MSG', 'Internal Server Error');

use Phroute\Phroute\RouteCollector;
use Phroute\Phroute\Dispatcher;
use Phroute\Phroute\Exception\HttpRouteNotFoundException;
use Phroute\Phroute\Exception\HttpException;
use Phroute\Phroute\Exception\BadRouteException;

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
} catch (HttpRouteNotFoundException $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 ' . $e->getMessage());
} catch (HttpException $e) {
    $code = $e->getCode();
    $msg = $e->getMessage();
    header($_SERVER['SERVER_PROTOCOL'] . " $code");
    echo json_encode([ 
        'status' => $code,
        'error' => $msg
    ]);
} catch (BadRouteException|\Exception $e) {
    $client_msg = 'Internal Server Error';
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 ' . CLIENT_500_MSG);
    echo json_encode([ 
        'status' => 500,
        'error' => CLIENT_500_MSG
    ]);
}
