<?php
namespace TsukudolAPI;
require __DIR__ . '/../vendor/autoload.php';

error_reporting(-1);

call_user_func(function(){
    $headers = getallheaders();
    $now = new \DateTimeImmutable;
    $app = new Application($headers, $_SERVER, $_COOKIE, $_GET, $_POST, $now);
    $router = new \Teto\Routing\Router($app->getRoutingMap());

    $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $action = $router->match($_SERVER['REQUEST_METHOD'], $path);
    $response = $app->execute($action);

    $app->sendHttpStatus();
    echo $app->renderResponse($response);
});

exit;
