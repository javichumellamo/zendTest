<?php

use Bookstore\Core\Config;
use Bookstore\Core\Router;
use Bookstore\Core\Request;
use Bookstore\Utils\DependencyInjector;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();

$dbConfig = $config->get('db');

$database = 'bookstore';

//CONEXION con base de datos
$db = new PDO(
    "mysql:host=127.0.0.1;dbname=$database", $dbConfig["user"], $dbConfig["password"]);

//carga del sistema de plantillas twig
$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
$view = new Twig_Environment($loader);

$log = new Logger('bookstore');
$logFile = $config->get('log');
$log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));

//creacion d elas dependencias a usar en el proyecto
$di= new DependencyInjector();
$di->set("PDO", $db);
$di->set("Utils\Config", $config);
$di->set("Twig_Environment", $view);
$di->set("Logger", $log);


//lanzamiento de la primera request para activar el sistema cliente/servidor
$router = new Router($di);
$response = $router->route(new Request());
echo $response;