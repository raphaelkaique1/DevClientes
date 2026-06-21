<?php
declare(strict_types=1);
require __DIR__ . '/config/URL.php';
require_once __DIR__ . '/src/Router.php';
require_once __DIR__ . '/src/Controllers/CustomerController.php';

$router = new Router();
$customerController = new CustomerController();

$router->get('/api/customers', [$customerController, 'index']);
$router->post('/api/customers', [$customerController, 'store']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $PATH);

?>