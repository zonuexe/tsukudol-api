<?php
namespace TsukudolAPI;
require __DIR__ . '/../vendor/autoload.php';

error_reporting(-1);

call_user_func(function(){
    $now = new \DateTimeImmutable;
    $app = new Application($_SERVER, $_COOKIE, $_GET, $_POST, $now);
    $router = new \Teto\Routing\Router($app->getRoutingMap());

    $basedir = dirname(__DIR__);
    $twig_option = [
        'cache' => $basedir . '/cache/twig',
        'debug' => true,
    ];

    $loader = new \Twig_Loader_Filesystem($basedir . '/src/View/twig');
    \Baguette\Response\TwigResponse::setTwigEnvironment(new \Twig_Environment($loader, $twig_option));

    $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $action = $router->match($_SERVER['REQUEST_METHOD'], $path);
    $response = $app->execute($action);

    echo $app->renderResponse($response);
});

exit;
