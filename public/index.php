<?php

date_default_timezone_set("America/Argentina/Buenos_Aires");

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Config\Database;

use App\Controllers\UserController;
use App\Controllers\MascotaController;
use App\Controllers\TurnoController;
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\ClienteMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$conn = new Database;
$app = AppFactory::create();
$app->setBasePath("/TrabajoEnClases/SegundoParcial/public");

$app->add(new JsonMiddleware);


$app->post('/users[/]', UserController::class.":addOne");
$app->post('/login[/]', UserController::class.":login");
$app->post('/mascota[/]', MascotaController::class.":addOne")->add(new AdminMiddleware);
$app->post('/turno[/]', TurnoController::class.":addOne")->add(new ClienteMiddleware);
$app->put('/turno/{idTurno}[/]', TurnoController::class.":atenderTurnos")->add(new AdminMiddleware);



$app->addBodyParsingMiddleware();

$app->run();
