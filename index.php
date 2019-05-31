<?php

use function App\eventStoreInit;
use function App\routesInit;
use Middleware\Authenticate;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
//$app['debug'] = true;

//Uwierzytelnianie
$app->before(function($request, $app) {
    if(!Authenticate::authenticate($request)) {
        return new Response("Forbidden", 403);
    }
});

// Rejestracja specjalnego modułu rozszerzającego możliwość tworzenia klas
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Tworzenie klas
routesInit($app, eventStoreInit());

$app->run();

