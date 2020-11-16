<?php

/*
    Author: Vallejo Juan Marcos - Base slim application.
*/

use Enum\UserRole;

use Config\Database;
use Slim\Factory\AppFactory;
use Middlewares\JsonMiddleware;
use Slim\Routing\RouteCollectorProxy;
use Middlewares\Authentication\AuthMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Controllers\InscripcionController;
use Controllers\NotasController;
use Controllers\LoginController;
use Controllers\UserController;
use Controllers\MateriaController;
use Middlewares\FormDataMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$conn = new Database();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Servicio funcando correctamente.");
    return $response;
});

$app->post('/file', FileController::class . ":uploadFile");

/* -- Users -- */
$app->group('/users', function (RouteCollectorProxy $group) {
    /* Punto 1 */
    $group->post('[/]', UserController::class . ":addOne");
});


$app->group('/materia', function (RouteCollectorProxy $group) {
    /* Punto 3 */
    $group->post('[/]', MateriaController::class . ":addOne")->add(new AuthMiddleware([UserRole::ADMIN]));
    /* Punto 7 */
    $group->get('[/]', MateriaController::class . ":getAll")->add(new AuthMiddleware([UserRole::ADMIN, UserRole::PROFESOR, UserRole::ALUMNO]));
});

$app->group('/inscripcion', function (RouteCollectorProxy $group) {
    /* Punto 4 */
    $group->post('[/]', InscripcionController::class . ":addOne")->add(new AuthMiddleware([UserRole::ALUMNO]));

    /* Punto 6 */
    $group->get('/{idMateria}', InscripcionController::class . ":getAll")->add(new AuthMiddleware([UserRole::ADMIN, UserRole::PROFESOR]));
});

$app->group('/notas', function (RouteCollectorProxy $group) {
    /* Punto 5 */ /* Se hizo por FORM URL ENCODED */
    $group->put('[/]', NotasController::class . ":addOne")->add(new AuthMiddleware([UserRole::PROFESOR]));

    /* PPunto 8 */
    $group->get('/{idMateria}', NotasController::class . ":getNotas")->add(new AuthMiddleware([UserRole::ADMIN, UserRole::PROFESOR, UserRole::ALUMNO]));
});

/* Authentication */
$app->group('/login', function (RouteCollectorProxy $group) {
    /* Punto 2 */
    $group->post('[/]', LoginController::class . ":login");

    $group->get('[/]', LoginController::class . ":getRole")->add(new AuthMiddleware([UserRole::ADMIN]));
});

$app->add(new JsonMiddleware());
$app->addBodyParsingMiddleware();
//$app->addErrorMiddleware(false, true, true);

$app->run();
