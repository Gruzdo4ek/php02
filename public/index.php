<?php

require_once '../vendor/autoload.php';
require_once "../framework/autoload.php";
require_once "../controllers/MainController.php";
require_once "../controllers/BaseDogTwigController.php";
require_once "../controllers/Controller404.php";
require_once "../controllers/ObjectController.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/DogObjectCreateController.php";
require_once "../controllers/DogObjectDeleteController.php";
require_once "../controllers/DogObjectUpdateController.php";
require_once "../middlewares/LoginRequiredMiddleware.php";
require_once "../controllers/SetWelcomeController.php";
require_once "../controllers/LoginController.php";
require_once "../controllers/LogoutController.php";
require_once "../controllers/function.php";

session_set_cookie_params(60*60*10);
session_start();

$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
    "debug" => true // добавляем тут debug режим
]);
$twig->addExtension(new \Twig\Extension\DebugExtension()); // и активируем расширение
$twig->addFilter(new \Twig\TwigFilter('url_decode','decodeURL'));

$pdo = new PDO("mysql:host=localhost;dbname=dogs;charset=utf8", "root", "");

$router = new Router($twig, $pdo);
$router->add("/", MainController::class)->middleware(new LoginRequiredMiddleware());
$router->add("/dogs/(?P<id>\d+)", ObjectController::class)->middleware(new LoginRequiredMiddleware());
$router->add("/search", SearchController::class)->middleware(new LoginRequiredMiddleware());

$router->add("/dogs/create", DogObjectCreateController::class)->middleware(new LoginRequiredMiddleware());
$router->add("/dogs/(?P<id>\d+)/delete", DogObjectDeleteController::class)->middleware(new LoginRequiredMiddleware());
$router->add("/dogs/(?P<id>\d+)/edit", DogObjectUpdateController::class)->middleware(new LoginRequiredMiddleware());

$router->add("/set-welcome", SetWelcomeController::class);
$router->add("/login", LoginController::class);
$router->add("/logout", LogoutController::class);

$router->get_or_default(Controller404::class);