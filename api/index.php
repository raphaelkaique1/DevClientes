<?php
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Controllers/CustomerController.php';

$router = new Router();
$customerController = new CustomerController();

$router->get('/api/customers', [$customerController, 'index']);
$router->post('/api/customers', [$customerController, 'store']);
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

?>