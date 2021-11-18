<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Controllers\ArticleController;
use Illuminate\Database\Capsule\Manager as DB;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$db = new DB();
$config= parse_ini_file(__DIR__."/config/conf.ini");
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();

$config = require_once __DIR__ .'/config/settings.php';
$container = new \Slim\Container($config);
$app = new \Slim\App($container);

$app->get('/', function (Request $rq, Response $rs, array $args):Response{
    $control = new ArticleController($this);
    return $control->listArticles($rq, $rs, $args)->withHeader("Content-Type", "application/json");
});

$app->run();
